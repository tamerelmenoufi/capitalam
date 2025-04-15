    <!-- ======= FGTS Section ======= -->
    <style>
        .fluxo{
            width:90%;
            height:50px;
            position:relative;
            border:solid 0px red;
            text-align:center;
        }
        .linha{
            position:absolute;
            height:10px;
            background-color:#1c4a9b;
            border:0;
            left:0;
            top:20px;
        }
        .linha2{
            position:absolute;
            width:100%;
            height:10px;
            background-color:#ccc;
            border:0;
            left:0;
            top:20px;
        }
        .etapas{
            position:absolute;
            top:5px;
            font-size:40px;
            color:#1c4a9b;
            background-color:#fff;
        }
        .legenda{
            position:absolute;
            top:50px;
            font-size:12px;
            color:#1c4a9b;
            width:100px;
            border:solid 0px red;
        }
        i[etapa], div[etapa]{
            cursor:pointer;
        }
    </style>
    <section id="fgts" class="team">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Antecipação de FGTS</h2>
        </div>
        <div style="font-size:14px; color:#a1a1a1; font-weight:bold; width:100%; text-align:center">Etapas para solicitação</div>
        <div class="row gy-5">
            <div class="d-flex justify-content-center">
                <div class="fluxo">
                    <div class="linha2"></div>
                    <div class="linha"></div>

                    <i etapa="fgts/autorizacao.php" acao="blq" class="fa-regular fa-circle etapas" style="left:calc(0% - 5px)"></i>
                    <i etapa="fgts/home.php" acao="blq" class="fa-regular fa-circle etapas" style="left:calc(25% - 15px)"></i>
                    <i etapa="fgts/saldo.php" acao="blq" class="fa-regular fa-circle etapas" style="left:calc(50% - 20px)"></i>
                    <i etapa="fgts/cadastro.php" acao="blq" class="fa-regular fa-circle etapas" style="left:calc(75% - 25px)"></i>
                    <i etapa="fgts/consulta.php" acao="blq" class="fa-regular fa-circle etapas" style="left:calc(100% - 35px)"></i>

                    
                    <div etapa="fgts/autorizacao.php" acao="blq" class="legenda" style="left:calc(0% - 35px)">Autorização<br>Para FGTS</div>
                    <div etapa="fgts/home.php" acao="blq" class="legenda" style="left:calc(25% - 45px)">Pré<br>Cadastro</div>
                    <div etapa="fgts/saldo.php" acao="blq" class="legenda" style="left:calc(50% - 50px)">Consultar<br>Saldo</div>
                    <div etapa="fgts/cadastro.php" acao="blq" class="legenda" style="left:calc(75% - 55px)">Cadastro<br>Completo</div>
                    <div etapa="fgts/consulta.php" acao="blq" class="legenda" style="left:calc(100% - 65px)">Antecipação<br>FGTS</div>

                </div>
            </div>
        </div>
      </div>


      <div class="row g-0" style="margin-top:50px;">
        <div class="col">
            <div class="palco m-3"></div>
        </div>
      </div>


    </section><!-- End Team Section -->

    <script>
        $(function(){


            <?php
            if($_GET['c']){
            ?>
            localStorage.setItem("codUsr", <?=$_GET['c']?>);
            <?php
            }
            ?>

            codUsr = localStorage.getItem("codUsr");

            if(codUsr){


                $.ajax({
                    url:"fgts/sessao.php",
                    type:"POST",
                    data:{
                        codUsr
                    },
                    success:function(dados){
                        $.ajax({
                            url:"fgts/home.php",
                            success:function(dados){
                                $(".palco").html(dados);
                            }
                        })
                    }
                })   


            }else{
                $.ajax({
                    url:"fgts/index.php",
                    success:function(dados){
                        $(".palco").html(dados);
                    }
                })                
            }

            setInterval(() => {
                codUsr = localStorage.getItem("codUsr");
                $.ajax({
                    url:"fgts/sessao.php",
                    type:"POST",
                    data:{
                        codUsr
                    },
                    success:function(dados){
                        // $(".palco").html(dados);
                        console.log(dados)
                    }
                })                   
            }, 5000);

            $("i[etapa], div[etapa]").click(function(){
                url = $(this).attr("etapa");
                acao = $(this).attr("acao");
                if(acao == 'blq') return false;
                $.ajax({
                    url,
                    success:function(dados){
                        $(".palco").html(dados);
                    }
                })                  
            })


        })
    </script>