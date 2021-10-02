                          
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
         
        <!-- ---------------- JQUERY JS ------------------------ -->

        <script src="../../library/jquery/jquery.js"></script>

        <!-- ---------------- BOOTSTRAP-MULTISELECT JS ------------------------ -->

        <script src="../../library/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>

        <!-- ---------------- BOOTSTRAP JS ------------------------ -->

        <script src="../../library/bootstrap4/js/bootstrap.bundle.min.js"></script>

        <!-- ---------------- TOAST JS ------------------------ -->
        
        <script src="../../library/bootstrap4-toggle-3.6.1/js/bootstrap4-toggle.min.js"></script>

        <!-- ---------------- WOW JS ------------------------ -->

        <script src="../../library/wow/wow.min.js"></script>

        <script src="../../library/chart-js/dist/Chart.min.js"></script>
        
        <!-- ------------------------------ DataTable JS --------------------------------- -->

        <script src="../../library/DataTables/js/jquery.dataTables.min.js"></script>
        <script src="../../library/DataTables/js/dataTables.bootstrap4.js"></script>
        
        <script src="../../library/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../../library/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

        <script src="../../library/jszip/jszip.min.js"></script>
        <script src="../../library/pdfmake/pdfmake.min.js"></script>
        <script src="../../library/pdfmake/vfs_fonts.js"></script>
        <script src="../../library/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../../library/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../../library/datatables-buttons/js/buttons.colVis.min.js"></script>

        <!-- ---------------- PDF Iframe JS ------------------------ -->

        <script src="../../library/pdf/jquery.media.js"></script>

        <!-- ---------------- TOAST JS ------------------------ -->

        <script src="../../library/toast/toastr.min.js"></script>

        <!-- ------------------ X - EDITABLE ---------------------- -->

        <script src="../../library/DataTable/js/x-editable.js"></script>

        <!-- --------------------- DROPZONE ------------------------ -->

        <script src="../../library/dropzone/dist/dropzone.js"></script>
        

        <script src="../../js/main.js"></script>

        <!-- ---------------- SIDEBAR JS ------------------------ -->

        <script src="../../js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="../../js/scrollbar/mCustomScrollbar-active.js"></script>

        <!--------------------- Jquery-Validation.js ------------------->
        <script src="../../library/Jquery_Validation/dist/jquery.validate.min.js"></script>
        
        <script type="text/javascript" src="functions.js"></script>

        <script type="text/javascript">
            var totalHeight = parseInt(window.screen.availHeight);
            var occuptedHeight = parseInt($("#headBas").height()+$("#foot").height());
            var user = parseInt($("#userInfo").height());
            var round = parseInt($("#rounde").height());
            var side = parseInt(totalHeight-(user+round+(100)));
            var foot = parseInt(totalHeight-(user+round+(130)));
            var difHeight = totalHeight - occuptedHeight;
            var haut = parseInt($("#headHaut").height());
            
            $("body").css('overflowY','hidden'); 
            $("#wrapper").css('overflowY','hidden'); 
            document.getElementById("sub_body").style.maxHeight=difHeight+"px"; 
            document.getElementById("sub_body").style.overflowY="auto";
            document.getElementById("sub_body").style.overflowX="hidden"; 
                
            //document.getElementById("wrapper").style.maxHeight=difHeight+10+"px";
            document.getElementById("fixeHeight").style.maxHeight=side+10+"px";
            document.getElementById("fixeHeight").style.overflowY="auto";
            document.getElementById("fixeHeight").style.overflowX="hidden";
            document.getElementById("fenetre").style.maxHeight=foot+"px";
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

    </body>
</html>