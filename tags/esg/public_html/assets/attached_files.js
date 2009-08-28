function saveFileData(fileId)
{   
   var langs = ["uk", "ru", "en"];
   for (i = 0; i < langs.length; i++)
   {
       file_name = $('#at_file_name_' + langs[i] + '_' + fileId).val();
       description = $('#at_file_description_' + langs[i] + '_' + fileId).val();
       $.post("/admin/managestaticpages/updateFileData", { fileName: file_name, description: description, fileId: fileId, id: $('#elementId').val(), lang: langs[i] });
   }
   alert('Изменения сохранены');
}

function detachFile(fileId)
{
   if(confirm('Вы действительно хотите открепить файл?')) {
   $.post("/admin/managestaticpages/detachFile", { fileId: fileId, id: $('#elementId').val() },
   function(data){
     $('#attachedFiles').html(data);
   });
   $.post("/admin/managestaticpages/getAttachedFilesCount", { id: $('#elementId').val() },
   function(data){
     if(data == '0')
     {
        $('.n-information').hide();
     }
     $('#attachedFilesCount').html(data);
   });

   
   } else {
   return false;
   }

}