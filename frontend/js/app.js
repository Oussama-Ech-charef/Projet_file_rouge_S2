document.addEventListener("DOMContentLoaded", function () {
  const config = window.CabinetPage;
  if (!config) return;
  const root = document.querySelector("[data-app]");
  const form = document.querySelector("#record-form");
  const fields = document.querySelector("#form-fields");
  const tableBody = document.querySelector("#table-body");
  const feedback = document.querySelector("#feedback");
  const submit = document.querySelector("#submit-record");
  let records = [];
  let related = {};

  const escapeHtml = value => String(value ?? "").replace(/[&<>"']/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));
  const label = (field, value) => field.options?.[value] || value || "—";
  const showMessage = (message, kind = "success") => {
    feedback.textContent = message;
    feedback.className = `mb-5 rounded-xl px-4 py-3 text-sm ${kind === "error" ? "bg-red-50 text-red-800" : "bg-emerald-50 text-emerald-800"}`;
    feedback.hidden = false;
  };

  async function request(url, options = {}) {
    const response = await fetch(url, options);
    const payload = await response.json();
    if (!response.ok || !payload.success) throw new Error(payload.message || "La requête a échoué.");
    return payload.data;
  }

  async function loadRelated() {
    for (const [key, relation] of Object.entries(config.relations || {})) {
      related[key] = await request(relation.url);
    }
  }

  function drawFields(record = {}) {
    fields.innerHTML = config.fields.map(field => {
      const value = record[field.name] ?? field.default ?? "";
      const common = `id="field-${field.name}" name="${field.name}" ${field.required ? "required" : ""} class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition focus:border-orange-500 focus:ring-4 focus:ring-orange-100"`;
      const title = `<label for="field-${field.name}" class="block text-sm font-medium text-slate-700">${escapeHtml(field.label)}${field.required ? " <span class='text-orange-600'>*</span>" : ""}</label>`;
      if (field.type === "textarea") return `<div class="${field.wide ? "sm:col-span-2" : ""}">${title}<textarea ${common} rows="${field.rows || 3}" placeholder="${escapeHtml(field.placeholder || "")}">${escapeHtml(value)}</textarea></div>`;
      if (field.type === "select") {
        const choices = field.relation ? (related[field.relation] || []).map(row => `<option value="${escapeHtml(row[field.valueKey])}" ${String(value) === String(row[field.valueKey]) ? "selected" : ""}>${escapeHtml(field.optionLabel(row))}</option>`).join("") : Object.entries(field.options || {}).map(([v, text]) => `<option value="${escapeHtml(v)}" ${String(value) === v ? "selected" : ""}>${escapeHtml(text)}</option>`).join("");
        return `<div class="${field.wide ? "sm:col-span-2" : ""}">${title}<select ${common}><option value="">Choisir…</option>${choices}</select></div>`;
      }
      return `<div class="${field.wide ? "sm:col-span-2" : ""}">${title}<input type="${field.type || "text"}" ${common} value="${escapeHtml(value)}" placeholder="${escapeHtml(field.placeholder || "")}"></div>`;
    }).join("");
  }

  function fieldValue(field, value) {
    if (field.relation) {
      const row = (related[field.relation] || []).find(item => String(item[field.valueKey]) === String(value));
      return row ? field.optionLabel(row) : "—";
    }
    return label(field, value);
  }

  function render() {
    if (!records.length) {
      tableBody.innerHTML = `<tr><td colspan="${config.columns.length + 1}" class="px-5 py-16 text-center"><p class="font-semibold text-slate-800">Aucun résultat pour le moment</p><p class="mt-1 text-sm text-slate-500">Ajoutez votre premier élément avec le bouton ci-dessus.</p></td></tr>`;
      return;
    }
    tableBody.innerHTML = records.map(record => `<tr class="border-t border-slate-100 hover:bg-slate-50/70">${config.columns.map(column => `<td class="px-5 py-4 text-sm text-slate-600">${escapeHtml(fieldValue(column, record[column.name]))}</td>`).join("")}<td class="whitespace-nowrap px-5 py-4 text-right"><button data-edit="${record[config.id]}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-orange-500">Modifier</button><button data-delete="${record[config.id]}" class="rounded-lg px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-red-500">Supprimer</button></td></tr>`).join("");
  }

  async function refresh() {
    try {
      await loadRelated();
      records = await request(config.API_URL);
      render();
      document.querySelector("#record-count").textContent = `${records.length} ${records.length > 1 ? config.plural : config.singular.toLowerCase()}`;
    } catch (error) {
      tableBody.innerHTML = `<tr><td colspan="${config.columns.length + 1}" class="px-5 py-14 text-center text-sm text-red-700">${escapeHtml(error.message)} Vérifiez que le serveur PHP est lancé, puis actualisez la page.</td></tr>`;
      showMessage(error.message, "error");
    }
  }

  function openForm(record = null) {
    document.querySelector("#editor").hidden = false;
    form.reset();
    document.querySelector("#form-title").textContent = record ? `Modifier ${config.singular.toLowerCase()}` : `Ajouter ${config.singular.toLowerCase()}`;
    document.querySelector("#record-id").value = record ? record[config.id] : "";
    submit.textContent = record ? "Enregistrer les modifications" : "Ajouter";
    drawFields(record || {});
    form.hidden = false;
    form.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  document.querySelector("#add-record").addEventListener("click", () => openForm());
  document.querySelectorAll("#cancel-form, #cancel-form-bottom").forEach(button => button.addEventListener("click", () => { document.querySelector("#editor").hidden = true; }));
  form.addEventListener("submit", async event => {
    event.preventDefault();
    submit.disabled = true;
    const id = document.querySelector("#record-id").value;
    const body = Object.fromEntries(new FormData(form).entries());
    delete body.record_id;
    try {
      await request(id ? `${config.API_URL}?id=${encodeURIComponent(id)}` : config.API_URL, {
        method: id ? "PUT" : "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(body)
      });
      form.hidden = true;
      showMessage(id ? `${config.singular} modifié avec succès.` : `${config.singular} ajouté avec succès.`);
      await refresh();
    } catch (error) { showMessage(error.message, "error"); }
    finally { submit.disabled = false; }
  });

  tableBody.addEventListener("click", async event => {
    const edit = event.target.closest("[data-edit]");
    const remove = event.target.closest("[data-delete]");
    if (edit) openForm(records.find(row => String(row[config.id]) === edit.dataset.edit));
    if (remove) {
      const record = records.find(row => String(row[config.id]) === remove.dataset.delete);
      if (!record || !window.confirm(`Supprimer cet élément ? Cette action est définitive.`)) return;
      try {
        await request(`${config.API_URL}?id=${encodeURIComponent(remove.dataset.delete)}`, { method: "DELETE" });
        showMessage(`${config.singular} supprimé.`);
        await refresh();
      } catch (error) { showMessage(error.message, "error"); }
    }
  });

  document.querySelector("#mobile-menu").addEventListener("click", () => document.querySelector("#sidebar").classList.toggle("hidden"));
  refresh();
});
