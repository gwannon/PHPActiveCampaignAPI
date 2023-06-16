# PHPActiveCampaignAPI
Librería de PHP para trabajar contra la API de ActiveCampaign. Permite crear, editar y borrar contactos y cuentas. 
Permite poner/quitar etiquetas, inscribir/desuscribir en listas y ejecutar automatizaciones para un contacto. 

## Instalación
En config.dist.php tienes el código para establecer la conexión con tu API Key de ActiveCampapign para que metas en tu proyecto.
También estás los arrays de etiquetas, campos y listas con los que queires trabajar.

## Notas
Los cambios en los campos de el contacto o de la cuenta no se trasladan a AC hasta que no se ejecuta las funciones update. Es una 
forma de lanzarlo todo junto y hacer una sola llamada en vez de 4. Las etiqueta y listas si se ejecuta la llamada a la api por cada etiqueta. 
