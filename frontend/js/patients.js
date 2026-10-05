const API_URL = "http://localhost:8000/backend/admin/api/gestionPatient.php";
window.CabinetPage = {
  API_URL, id: "id_patient", singular: "Patient", plural: "patients",
  columns: [{name:"nom"},{name:"prenom"},{name:"date_naissance"},{name:"telephone"},{name:"email"}],
  fields: [
    {name:"nom",label:"Nom",required:true},{name:"prenom",label:"Prénom",required:true},
    {name:"date_naissance",label:"Date de naissance",type:"date"},{name:"telephone",label:"Téléphone",type:"tel"},
    {name:"email",label:"E-mail",type:"email",wide:true}
  ]
};
