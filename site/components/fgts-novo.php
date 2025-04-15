<?php

  $query = "select * from servicos where codigo = '{$_GET['cod']}'";
  $result = sisLog( $query);
  $d = mysqli_fetch_object($result);

?>
<style>

.botaoazul{
  background: var(--color-primary);
  border: 0;
  padding: 10px 35px;
  color: #fff;
  transition: 0.4s;
  border-radius: 0;
}


.botaoazul{
    background: var(--color-primary);
    border: 0;
    padding: 10px 35px;
    color: #fff;
    transition: 0.4s;
    border-radius: 0;


  }



  .recent-blog-posts .post-box .post-title {
    font-size: 24px;
    color: var(--color-secondary);
    font-weight: 700;
    margin: 15px 0 0 0;
    position: relative;
    transition: 0.3s;
}

.recent-blog-posts .post-box .post-title:hover {
    font-size: 24px;
    color: #574ec2;
    font-weight: 700;
    margin: 15px 0 0 0;
    position: relative;
    transition: 0.3s;
}
    .botaoverde{
      padding: 15px;
      padding-left: 35px;
      padding-right: 35px;
      border-radius: 25px 2px 25px;
      font-size: 17px;
      color: #fff;
    background-color: #393287;
    border-color: #393287;
    }
    .botaoverde:hover {
    color: #fff;
    background-color: #574ec2;
    border-color: #574ec2;
}
    
    .botaoroxo:hover {
    color: #fff;
    background-color: #574ec2;
    border-color: #574ec2;
}
 .botaoroxo {
    color: #fff;
    background-color: #393287;
    border-color: #393287;
}
.botao {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, 
    border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;

  }

  .breadcrumbs {
    padding: 15px 0;
    background: rgba(var(--color-secondary-rgb), 0.05);
    min-height: 40px;
    margin-top: 90px;
}

</style>

    <!-- ======= Breadcrumbs ======= -->
   
    </div><!-- End Breadcrumbs -->

    <!-- ======= Blog Details Section ======= -->
    <section id="blog" class="blog">
    <div class="container  mt-5">  
    <div class="row" style="padding:10px">

        <div class=" col-md-6 offset-md-3 card border-0" style="border-radius:10px;box-shadow: 2px 2px 13px 3px #144397; padding:10px">
            <div class="card-header bg-white border-0">
                <h2 class="m-0 align-middle" style="text-align:center;color: #144397;">Simulação do FGTS</h2>
                      </div>
            <div class="card-body">
                <p class="mt-2 font-16">Para continuar, informe-nos os dados abaixo:</p>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control  rounded-pill" id="name" placeholder="Nome completo">
                    <label for="name">Nome completo <span class="text-danger">*</span></label>
                </div>

                <div class="row">
                <div class="form-floating mb-3 col-6">
                    <input type="text" class="form-control  rounded-pill" id="cpf" placeholder="CPF">
                    <label style="margin-left:10px" for="cpf">CPF <span class="text-danger">*</span></label>
                </div>
                <div class="form-floating mb-3 col-6">
                    <input type="text" class="form-control  rounded-pill" id="birthDate" placeholder="Data de nascimento">
                    <label style="margin-left:10px;font-size:13px" for="birthDate">Data de nascimento <span class="text-danger">*</span></label>
                </div>
              </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control  rounded-pill" id="whatsappNumber" placeholder="Telefone WhatsApp">
                    <label for="whatsappNumber">Telefone WhatsApp <span class="text-danger">*</span></label>
                </div>
                <p class="m-0 mt-4 font-weight-bold">Todos os campos acima são obrigatórios</p>
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" value="" id="term">
                    <label class="form-check-label" for="term">
                       Autorizar que a <strong>CAPITAL SOLUÇÕES</strong>
                        entre em contato com você por celular, e-mail ou WhatsApp.
                    </label>
                </div>
            </div>
           
            <div class="text-center">
              <button type="submit" class="botaodiferente" style="
               
                background: #ffffff;
                border: 0;
                padding: 13px 70px;
                color: #144397;
                transition: 0.4s;
                border-radius: 25px;
                border-left: 10px #144397 solid;
                border-right: #144397 10px solid;
                border-top: #144397 solid 1px;
                border-bottom: #144397 solid 1px;
                margin-top: 10px;
                margin-bottom:10px;
              
              ">Continuar</button></div>



        </div>
 
        </div>
            </div>
    </section><!-- End Blog Details Section -->





