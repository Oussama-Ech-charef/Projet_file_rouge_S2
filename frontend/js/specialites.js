const API_URL = "http://localhost:8000/backend/admin/api/gestionSpecialite.php";
window.CabinetPage = {
  API_URL, id: "id_specialite", singular: "Spécialité", plural: "spécialités",
  columns: [{name:"nom_specialite"},{name:"description"}],
  fields: [{name:"nom_specialite",label:"Nom de la spécialité",required:true},{name:"description",label:"Description",type:"textarea",wide:true}]
};
