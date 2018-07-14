<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Attachments File</h4>
        </div>
        <div class="modal-body">
            <div id="myExcelDiv">
				<iframe width="500" height="200" frameborder="0" scrolling="no" src=" https://host/personal/user/_layouts/15/guestaccess.aspx?guestaccesstoken=2UdAHGlFpWVaJjkI32xuisKCQsULG6M6b%2fIjG1LYpRM%3d&docid=166d02b42f5a1443781a1de428d9518ee&action=embedview&wdbipreview=true&wdHideSheetTabs=true&wdAllowInteractivity=True& Item=PivotTable1& ActiveCell=B4&wdHideGridlines=True &wdHideHeaders=True& wdDownloadButton=True”>
			</div>
		</div>
    </div>
</div>
<script type="text/javascript" src="http://r.office.microsoft.com/r/rlidExcelWLJS?v=1&kip=1"></script>
<script type="text/javascript">

	/*
    * This code uses the Microsoft Office Excel JavaScript object model to programmatically insert the
    * Excel Web App into a div with id=myExcelDiv. The full API is documented at
    * http://msdn.microsoft.com/en-us/library/hh315812.aspx. There you can find out how to programmatically get
    * values from your Excel file and how to use the rest of the object model. 
    */

    // Use this file token to reference Book1.xlsx in the Excel APIs
    var fileToken = "SD310A16DD64ED7E41!112/3533661997762444865/";
    var ewa = null;

    // Run the Excel load handler on page load.
    if (window.attachEvent)
    {
        window.attachEvent("onload", loadEwaOnPageLoad);
    } else
    {
        window.addEventListener("DOMContentLoaded", loadEwaOnPageLoad, false);
    }

    function loadEwaOnPageLoad()
    {
        var props = {
            uiOptions: {
                showGridlines: false,
                showRowColumnHeaders: false,
                showParametersTaskPane: false
            },
            interactivityOptions: {
                allowTypingAndFormulaEntry: false,
                allowParameterModification: false,
                allowSorting: false,
                allowFiltering: false,
                allowPivotTableInteractivity: false
            }
        };
        // Embed workbook using loadEwaAsync
        Ewa.EwaControl.loadEwaAsync(fileToken, "myExcelDiv", props, onEwaLoaded);
    }

    function onEwaLoaded(asyncResult)
    { 
        if (asyncResult.getSucceeded())
        {
            // Use the AsyncResult.getEwaControl() method to get a reference to the EwaControl object
            ewa = asyncResult.getEwaControl();
            ewa.add_activeCellChanged(cellChanged);
        }
        else
        {
            alert("Async operation failed!");
        }
        // ...
    }

    // Handle the active cell changed event.
    function cellChanged(rangeArgs)
    {
        // Use the RangeEventArgs object to get information about the range.
        var sheetName = rangeArgs.getRange().getSheet().getName();
        var col = rangeArgs.getRange().getColumn();
        var row = rangeArgs.getRange().getRow();
        var value = rangeArgs.getFormattedValues();
        alert("The active cell is located at row " + (row + 1) + " and column " + (col + 1) + " with value '" + value + "'.");
        // ...
    }

    $(document).ready( function() {
		//var file = "<?= base_url().'assets/uploads/'.$file;?>";
       // $('#show-file').load(file);        
    });
</script>
