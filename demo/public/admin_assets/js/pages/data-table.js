//[Data Table Javascript]

//Project:	Etikto Admin - Responsive Admin Template
//Primary use:   Used only for the Data Table

$(function () {
    "use strict";

    $('#example1').DataTable();
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    });
	
	
	$('#example').DataTable({
	    dom: 'Bfrtip',
	    buttons: [
	        {
	            extend: 'copy',
	            exportOptions: {
	                columns: ':not(.noExport)' // Exclude columns with the "noExport" class
	            }
	        },
	        {
	            extend: 'excel',
	            exportOptions: {
	                columns: ':not(.noExport)' // Exclude columns with the "noExport" class
	            }
	        },
	        {
	            extend: 'csv',
	            exportOptions: {
	                columns: ':not(.noExport)' // Exclude columns with the "noExport" class
	            }
	        },
	        // {
	        //     extend: 'pdf',
	        //     exportOptions: {
	        //         columns: ':not(.noExport)' // Exclude columns with the "noExport" class
	        //     }
	            // customize: function(doc) {
	            //     // Customizing PDF export (padding, margin, font, etc.)
	            //     doc.styles.tableHeader.fontStyle = 'bold';
	            //     doc.styles.tableHeader.fontSize = 12;
	            //     doc.styles.tableBody.fontSize = 10;

	            //     // Custom padding for cells (in points, default is 5)
	            //     doc.autoTable({
	            //         styles: {
	            //             cellPadding: 5, // Padding inside each cell
	            //             fontSize: 10, // Font size
	            //         },
	            //         margin: { top: 30, left: 20, bottom: 20, right: 20 }, // Set the margins
	            //         headStyles: {
	            //             fillColor: [255, 0, 0] // Example: Change header color
	            //         },
	            //         bodyStyles: {
	            //             fillColor: [255, 255, 255] // Example: Change body color
	            //         }
	            //     });
	            // }
	        // },
	        {
	            extend: 'print',
	            exportOptions: {
	                columns: ':not(.noExport)' // Exclude columns with the "noExport" class
	            }
	        }
	    ],
	    paging: false,
	    info: false,
	    lengthChange: true,
	    pageLength: 50
	});

	$('#example7').DataTable({
      'paging'      : false,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true
    });

	// $('#example7').DataTable( {
	// 	dom: 'Bfrtip',
	// 	paging : false,
	// 	info : false,
	// 	searching : true,
	// 	lengthChange: true,
	// 	pageLength : 50
	// } );
	
	$('#tickets').DataTable({
	  'paging'      : true,
	  'lengthChange': true,
	  'searching'   : true,
	  'ordering'    : true,
	  'info'        : true,
	  'autoWidth'   : false,
	});
	
	$('#productorder').DataTable({
	  'paging'      : true,
	  'lengthChange': true,
	  'searching'   : true,
	  'ordering'    : true,
	  'info'        : true,
	  'autoWidth'   : false,
	});
	

	$('#complex_header').DataTable();
	
	//--------Individual column searching
	
    // Setup - add a text input to each footer cell
    $('#example5 tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#example5').DataTable({
    	pageLength : 10,
    });
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
	
	
	//---------------Form inputs
	var table = $('#example6').DataTable();
 
    
	
	
	
	
  }); // End of use strict