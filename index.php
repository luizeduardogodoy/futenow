<?php

require_once 'inc/config.inc.php';
require_once 'inc/sessao.inc.php';

require_once 'dao/usuario.php';
require_once 'dao/local.php';
require_once 'dao/bairro.php';

$usuarioConectado = unserialize($_SESSION['AppFutNow_UserConnected']);

Echo $usuarioConectado->nome . ', bem vindo ao Futenow!  <a href="logout.php">Logout</a>';

require_once 'templates/index.html';

$sql  = "SELECT a.* FROM evento a ";
$sql .= "INNER JOIN local b ON a.local_id = b.id ";
$sql .= "INNER JOIN bairro c ON b.bairro_id = c.id ";
$sql .= "INNER JOIN cidade d ON c.cidade_id = d.id ";
$sql .= "WHERE hora_limite_confirmacao >= '" . Date('Y-m-d H:i:s') . "' ";
$sql .= "AND usuario_id <> " . $usuarioConectado->id . " ";
$sql .= "AND cidade_id = " . $usuarioConectado->cidade_id . " AND faltam > 0 ";

$res = $db->Execute($sql);

?>
	<script language="JavaScript">
	
		$(function(){
			initJqxGrid();			
		});
		
		var url = "inc/ajax.php?req=index";
			// prepare the data
			var source =
			{
				 datatype: "json",
				 datafields: [
					  { name: 'UsuarioNome' },
					  { name: 'LocalNome' },
					  { name: 'Observacao'},
					  { name: 'HoraInicio', type: 'date' },
					  { name: 'HoraConfirmacao', type: 'date' },
					  { name: 'Faltam', type: 'integer' },
					  { name: 'id', type: 'integer' }
					  
				 ],
				 id: 'id',
				 url: url,
				 root: 'data'
			};
			
		
		function reload(){
			var dataAdapter = new $.jqx.dataAdapter(source);
			
			$("#table").jqxGrid({source: dataAdapter});
			
			setTimeout("reload()",10000);
		}
		
		var chamado = false;
		
		function initJqxGrid(){
						
			$('#table').on('bindingcomplete', function (event) { // Some code here. 
				if(!chamado){
					reload();
					chamado = true;
				}
			});

			var dataAdapter = new $.jqx.dataAdapter(source);			
			$("#table").jqxGrid({
				theme: 'summer',
				width: "80%",
				height:250,
				source: dataAdapter,
				columnsresize: true,
				localization : getLocalization('en'),
				showStatusBar: true,
				 renderstatusbar: function (statusbar) {
                    // appends buttons to the status bar.
                    var container = $("<div style='overflow: hidden; position: relative; margin: 5px;'></div>");
                    var addButton = $("<div style='float: left; margin-left: 5px;'>Confirmar</div>");
                   /* var deleteButton = $("<div style='float: left; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='../../images/close.png'/><span style='margin-left: 4px; position: relative; top: -3px;'>Delete</span></div>");
                    var reloadButton = $("<div style='float: left; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='../../images/refresh.png'/><span style='margin-left: 4px; position: relative; top: -3px;'>Reload</span></div>");
                    var searchButton = $("<div style='float: left; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='../../images/search.png'/><span style='margin-left: 4px; position: relative; top: -3px;'>Find</span></div>");*/
                    container.append(addButton);
                   /* container.append(deleteButton);
                    container.append(reloadButton);
                    container.append(searchButton);*/
                    statusbar.append(container);
                    addButton.jqxButton({  width: 60, height: 20 });
                    /*deleteButton.jqxButton({  width: 65, height: 20 });
                    reloadButton.jqxButton({  width: 65, height: 20 });
                    searchButton.jqxButton({  width: 50, height: 20 });*/
                    // add new row.
                    addButton.click(function (event) {
                       
					   
					   
						var selectedrowindex = $("#table").jqxGrid('getselectedrowindex');
						
						if(selectedrowindex < 0){
							return;
						}
						
						var id  = $('#table').jqxGrid('getcellvalue',selectedrowindex,'id');
						
                        var rowscount = $("#table").jqxGrid('getdatainformation').rowscount;
						
                        var id = $("#table").jqxGrid('getrowid', selectedrowindex);
                        //$("#table").jqxGrid('deleterow', id);
						
						
						
						
						
						var content = $("<div id='popupConf'></div>");
						
						content.append("<table><tr><td>Id: </td><td>" + $('#table').jqxGrid('getcellvalue',selectedrowindex,'id') + "</td><tr>");
						content.append("<tr><td> Obs:</td> <td>" + $('#table').jqxGrid('getcellvalue',selectedrowindex,'Observacao') + "</td></tr>");
						content.append("<tr><td> Local: </td> <td>" + $('#table').jqxGrid('getcellvalue',selectedrowindex,'LocalNome') + "</td></tr>");
						content.append("<tr><td> Início: </td> <td>" + $('#table').jqxGrid('getcellvalue',selectedrowindex,'HoraInicio') + "</td></tr></table>");
						
						var btnConf = $("<button id='btnConf'>Confirmar</button>");
						content.append(btnConf);
						
						var btnCanc = $("<button id='btnConf'>Cancelar</button>");
						content.append(btnCanc);
						
						btnConf.jqxButton();
						btnCanc.jqxButton();
						
						$("#jqxwindow").jqxWindow({content: content, width:'450px', height:'350px'});
						$("#jqxwindow").jqxWindow({title: $('#table').jqxGrid('getcellvalue',selectedrowindex,'LocalNome')});
						
						var offset = $("#table").offset();
                        $("#jqxwindow").jqxWindow('open');
						$("#jqxwindow").jqxWindow('move', offset.left + 30, offset.top + 30);						
						
						
						
                    });
                  
                },
				 columns: [
					{ text: 'Criador', dataField: 'UsuarioNome', width: 200 },
					{ text: 'Local', dataField: 'LocalNome', width: 200 },
					{ text: 'Observações', dataField: 'Observacao', width: 350 },
					{ text: 'Início', dataField: 'HoraInicio', width: 150, cellsalign: 'center', cellsformat : "dd/M/yyyy H:mm"},
					{ text: 'Confirmar até', dataField: 'HoraConfirmacao', width: 150 ,cellsformat : "d", cellsalign: "center", cellsformat : "dd/M/yyyy H:mm"},
					{ text: 'Faltam', dataField: 'Faltam', cellsalign: 'right', width: 80 }
				 ]
			});			
		}
	</script>
<?



