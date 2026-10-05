const API_URL = "http://localhost:8000/backend/admin/api/gestionConsultation.php";
window.CabinetPage = {
  API_URL, id: "id_consultation", singular: "Consultation", plural: "consultations",
  relations: {
    patients:{url:"http://localhost:8000/backend/admin/api/gestionPatient.php"},
    specialites:{url:"http://localhost:8000/backend/admin/api/gestionSpecialite.php"}
  },
  columns: [
    {name:"date_heure"},
    {name:"id_patient",relation:"patients",valueKey:"id_patient",optionLabel:r=>`${r.prenom} ${r.nom}`},
    {name:"id_specialite",relation:"specialites",valueKey:"id_specialite",optionLabel:r=>r.nom_specialite},
    {name:"type_consultation",options:{presentiel:"Présentiel",teleconsultation:"Téléconsultation"}},
    {name:"statut",options:{planifiee:"Planifiée",terminee:"Terminée",annulee:"Annulée"}},
    {name:"motif"}
  ],
  fields: [
    {name:"id_patient",label:"Patient",type:"select",relation:"patients",valueKey:"id_patient",optionLabel:r=>`${r.prenom} ${r.nom}`,required:true},
    {name:"id_specialite",label:"Spécialité",type:"select",relation:"specialites",valueKey:"id_specialite",optionLabel:r=>r.nom_specialite,required:true},
    {name:"date_heure",label:"Date et heure",type:"datetime-local",required:true},
    {name:"type_consultation",label:"Type de consultation",type:"select",default:"presentiel",options:{presentiel:"Présentiel",teleconsultation:"Téléconsultation"}},
    {name:"statut",label:"Statut",type:"select",default:"planifiee",options:{planifiee:"Planifiée",terminee:"Terminée",annulee:"Annulée"}},
    {name:"motif",label:"Motif",type:"textarea",required:true,wide:true},
    {name:"diagnostic",label:"Diagnostic",type:"textarea",wide:true}
  ]
};
