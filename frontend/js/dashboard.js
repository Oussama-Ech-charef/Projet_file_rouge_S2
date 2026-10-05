document.addEventListener("DOMContentLoaded", function () {
  const endpoints = {
    consultations: "http://localhost:8000/backend/admin/api/gestionConsultation.php",
    specialites: "http://localhost:8000/backend/admin/api/gestionSpecialite.php",
    ordonnances: "http://localhost:8000/backend/admin/api/gestionOrdonnance.php",
    patients: "http://localhost:8000/backend/admin/api/gestionPatient.php"
  };
  document.querySelector("#mobile-menu").addEventListener("click", () => document.querySelector("#sidebar").classList.toggle("hidden"));
  Promise.all(Object.entries(endpoints).map(async ([name,url]) => {
    const response=await fetch(url); const payload=await response.json();
    if(!response.ok||!payload.success)throw new Error(payload.message||"Impossible de charger les données.");
    document.querySelector(`#count-${name}`).textContent=payload.data.length;
  })).then(() => { document.querySelector("#dashboard-updated").textContent="Données à jour"; })
    .catch(error => { const box=document.querySelector("#dashboard-error"); box.textContent=`${error.message} Lancez le serveur PHP puis actualisez la page.`; box.classList.remove("hidden"); document.querySelector("#dashboard-updated").textContent="Chargement impossible"; });
});
