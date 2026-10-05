const API_URL = "http://localhost:8000/backend/admin/api/gestionOrdonnance.php";
window.CabinetPage = {
  API_URL, id: "id_ordonnance", singular: "Ordonnance", plural: "ordonnances",
  relations: {
    consultations:{url:"http://localhost:8000/backend/admin/api/gestionConsultation.php"},
    patients:{url:"http://localhost:8000/backend/admin/api/gestionPatient.php"}
  },
  columns: [
    {name:"date_creation"},
    {name:"id_consultation",relation:"consultations",valueKey:"id_consultation",optionLabel:r=>`${r.date_heure.replace("T"," ")} · consultation #${r.id_consultation}`},
    {name:"contenu"},{name:"recommandations"}
  ],
  fields: [
    {name:"id_consultation",label:"Consultation",type:"select",relation:"consultations",valueKey:"id_consultation",optionLabel:r=>`${r.date_heure.replace("T"," ")} · consultation #${r.id_consultation}`,required:true},
    {name:"date_creation",label:"Date de création",type:"date",required:true},
    {name:"contenu",label:"Contenu de l’ordonnance",type:"textarea",required:true,wide:true},
    {name:"recommandations",label:"Recommandations",type:"textarea",wide:true}
  ]
};
