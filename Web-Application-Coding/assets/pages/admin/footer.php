                          
               <!-- Footer -->
                <footer class="fixed-footer bg-white mt-3" id="foot">
                    <div class="container">
                        <div class="copyright text-center my-auto py-4">
                            <p>
                                Design by bit first promotion student <i class="far fa-copyright"></i> 
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                
                            </p>
                        </div>
                    </div>
                </footer>

                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded bg-primary" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>
            </div>
            </div>
            <!-- End Main Conteneur-->
            </div> 
            </div>
        <!-- End Page Wrapper-->
        </div>
         

        <script src="../../library/jquery/jquery.js"></script>
        <script src="../../library/bootstrap4/js/bootstrap.bundle.js"></script>
        <script src="../../library/wow/wow.min.js"></script>
         <!-- data table JS ============================================ -->
        <script src="../../library/DataTable/js/bootstrap-table.js"></script>
        <script src="../../library/DataTable/js/tableExport.js"></script>

        <script src="../../library/DataTable/js/data-table-active.js"></script>

        <script src="../../library/DataTable/js/bootstrap-table-export.js"></script>
        <script src="../../library/DataTables/js/jquery.dataTables.min.js"></script>
        <script src="../../library/DataTables/js/dataTables.bootstrap4.js"></script>
        <script src="../../js/main.js"></script>

        <script src="../../simplebar/src/simplebar.js"></script>
        <script type="text/javascript">
        var totalHeight = parseInt(window.screen.availHeight);
        var occuptedHeight = parseInt($("#headBas").height()+$("#foot").height());
        var user = parseInt($("#userInfo").height());
        var round = parseInt($("#rounde").height());
        var side = parseInt(totalHeight-(user+round+(100)));
        var difHeight = totalHeight - occuptedHeight;
        var haut = parseInt($("#headHaut").height());
        
        $("body").css('overflowY','hidden'); 
        $("#wrapper").css('overflowY','hidden'); 
        document.getElementById("sub_body").style.maxHeight=difHeight+"px"; 
        document.getElementById("sub_body").style.overflowY="scroll";
        document.getElementById("sub_body").style.overflowX="hidden"; 
            
        //document.getElementById("wrapper").style.maxHeight=difHeight+10+"px";
        document.getElementById("fixeHeight").style.maxHeight=side+20+"px";
        document.getElementById("fixeHeight").style.overflowY="scroll";
        document.getElementById("fixeHeight").style.overflowX="hidden";
        //document.getElementById("fenetre").style.minHeight=difHeight+"px";
        //document.getElementById("fenetre").style.backgroundColor="green";
            //userInfo
//rounde 
       // alert(side);
            
       
     
        function VerifyIfScholarship(inputValue){
        var msg = $('#msg');
        var input = $('#submit_scholarship');
        for (var i = 0; i < sc.length; i++)
        {
            if (sc[i]==inputValue)
            {
                msg.show();
                input.addClass('disabled');
                break;
            }else if(inputValue==""){
               input.addClass('disabled');      
            }
            else
            {
                msg.hide();
                input.removeClass('disabled');
            }
        }
    }

</script>

        <script src="../../library/DataTable/js/data-table-active.js"></script>


    </body>
</html>