

<!-- PHP / SQL QUERY FOR "NEW REPORTS"    ========================================================================================= -->

<div style="background-color: gainsboro; padding: 15px;">
 <div class="d-flex flex-wrap gap-3 align-items-center">
    <h2>OCCURRENCE REPORT SUMMARY</h2>

    <!-- Apply ms-auto to push the buttons to the right and add spacing with me-2 -->
   <div class="d-flex justify-content-end mb-3 no-print ms-auto">
       <a href="edit_occur.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm me-2 no-print">Edit</a>
       <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="openPDF();">PDF</button>
		 <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="window.print();">Print</button>
       <button type="button" class="btn btn-warning btn-sm me-2 no-print" onclick="downloadPDF();">Download</button>
       <button type="button" class="btn btn-warning btn-sm no-print" onclick="emailPDF();">Email</button>
   </div>												
</div>


	<?php //include("pdf_report_detail.php"); ?>

</div>





