<?php

include("{$_SERVER['DOCUMENT_ROOT']}/painel/lib/includes.php");

list($bancos) = mysqli_fetch_row(sisLog( "select bancos from configuracoes where codigo = '1'"));
$bancos = json_decode($bancos);

$siglas = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];

if($_POST['acao'] == 'cadastro_percentual'){

    $campos_pendentes = json_encode($_POST['campos_pendentes'], JSON_UNESCAPED_UNICODE);
    $query = "update clientes set cadastro_percentual = '{$_POST['cadastro_percentual']}', campos_pendentes='{$campos_pendentes}' where codigo = '{$_SESSION['codUsr']}'";
    sisLog( $query);
    exit();

}

if($_POST['acao'] == 'salvar'){

    if($_POST['campo'] == 'cpf'){
        $query = "select * from clientes where cpf = '{$_POST['valor']}' and codigo != '{$_SESSION['codUsr']}'";
        $result = sisLog( $query);
        if(mysqli_num_rows($result)){
            echo 'error';
            exit();
        }
    }

    if($_POST['tipo'] == 'data'){
        $valor = dataMysql($_POST['valor']);
    }else{
        $valor = addslashes($_POST['valor']);
    }



    $extra = false;

    if($_POST['campo'] == 'pixKey'){
        $extra = ", accountNumber = '', accountDigit = '', branchNumber = '', accountType = '', bankCode = ''";
    }
    
    if(
            $_POST['campo'] == 'accountNumber' or 
            $_POST['campo'] == 'accountDigit' or 
            $_POST['campo'] == 'branchNumber' or 
            $_POST['campo'] == 'accountType' or 
            $_POST['campo'] == 'bankCode'){

        $extra = ", pixKey = ''";
    }

    $query = "update clientes set {$_POST['campo']} = '{$valor}' {$extra} where codigo = '{$_SESSION['codUsr']}'";
    sisLog( $query);
    echo 'success'.$query;;
    exit();

}

if($_POST['telefone']){

    $query = "select * from clientes where phoneNumber = '{$_POST['telefone']}'";
    $result = sisLog( $query);
    $d = mysqli_fetch_object($result);
    if(!$d->codigo){
        $query = "insert into clientes set phoneNumber = '{$_POST['telefone']}', data_cadastro = NOW(), ultimo_acesso = NOW(), validar_telefone = NOW()";
        $result = mysqli_query($con, $query);
        $_SESSION['codUsr'] = mysqli_insert_id($con); 
    }else{
        $_SESSION['codUsr'] = $d->codigo; 
    }

}


$query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
$result = sisLog( $query);
$d = mysqli_fetch_object($result);
$dC = $d;



?>

<style>

    .card{
        border-color:#1c4a9b,
    }
    .card-header{
        background-color:#1c4a9b;
        color:#fff;
    }
    .card-title{
        font-weight:bold;
        color:#1c4a9b;
    }
    .card-text{
        color:#1c4a9b;
    }
 .botao-capital{
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
    }

    .botao-capital:hover{
    background: #144397;
    border: 0;
    padding: 13px 70px;
    color: #ffffff;
    transition: 0.4s;
    border-radius: 25px;
    margin-top: 10px;
    margin-bottom: 10px;
    
}



</style>

<div class="" >
<div class="row">
<div class=" col-md-8 offset-md-2 card border-0" style=" padding:10px">

    <h2  style="text-align:center" class="">
        Cadastro Completo
</h2>
    <div class="card-body">
        <h5 class="card-title">Tela de Cadastro</h5>
        <p class="card-text">Formulário de Cadastro. Os dados a seguir são obrigatório para contratação da antecipação do FGTS.</p>
        <p class="card-text">Preenche com atenção todos os campos até que a barra de preenchimento esteja 100% concluída.</p>
        
        <span style="color:#a1a1a1">Barra de preenchimento</span>
        <div class="progress mb-3">
            <div class="progress-bar progress-bar-striped" id="progresso" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">10%</div>
        </div>



<div class="row">
                <div class="form-group col-md-8">
                    <label class="form-label" for="nome">Nome*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$d->nome?>">
                    <div class="form-text"></div>
                </div>

                
                <div class="form-group col-md-4">
                    <label class="form-label" for="cpf">CPF*</label>
                    <div style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" class="form-control"><?=$d->cpf?></div>
                    <div class="form-text"></div>
                </div>
</div>

<div class="row">
                <div class="form-group col-md-4">
                    <label class="form-label" for="birthdate">Data de Nascimento*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" tipo="data" name="birthdate" id="birthdate" class="form-control" placeholder="Data de Nascimento" value="<?=dataBr($d->birthdate)?>">
                    <div class="form-text"></div>
                </div>


                <div class="form-group col-md-4">
                    <label class="form-label" for="maritalStatus">Estado Civil*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="maritalStatus" id="maritalStatus" class="form-select">
                        <option value="">:: Selecione ::</option>
                        <option value="Solteiro" <?=(($d->maritalStatus == 'Solteiro')?'selected':false)?>>Solteiro</option>
                        <option value="Casado" <?=(($d->maritalStatus == 'Casado')?'selected':false)?>>Casado</option>
                        <option value="Uniao Estavel" <?=(($d->maritalStatus == 'Uniao Estavel')?'selected':false)?>>União Estável</option>
                        <option value="Divorciado" <?=(($d->maritalStatus == 'Divorciado')?'selected':false)?>>Divorciado</option>
                        <option value="Separado" <?=(($d->maritalStatus == 'Separado')?'selected':false)?>>Separado</option>
                        <option value="Viúvo" <?=(($d->maritalStatus == 'Viúvo')?'selected':false)?>>Viúvo</option>
                    </select>
                    <div class="form-text"></div>
                </div>



                <div class="form-group col-md-4">
                    <label  class="form-label" for="gender">Gênero*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="gender" id="gender" class="form-select">
                        <option value="">:: Selecione ::</option>
                        <option value="M" <?=(($d->gender == 'M')?'selected':false)?>>Masculino</option>
                        <option value="F" <?=(($d->gender == 'F')?'selected':false)?>>Feminino</option>
                    </select>
                    <div class="form-text"></div>
                </div>

</div>

<div class="row">
   
                <div class="form-group col-md-3">
                    <label class="form-label" for="nationality">Nacionalidade*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" style="border-radius:20px;box-shadow: 0px 0px 5px 1px #1c4a9b;" acao type="text" name="nationality" id="nationality" class="form-control" placeholder="Nacionalidade" value="<?=$d->nationality?>">
                    <div class="form-text"></div>
                </div>
                <div class="form-group col-md-5">
                    <label class="form-label" for="pep">Exposta Politicamente*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="pep" id="pep" class="form-select">
                        <option value="">:: Selecione ::</option>
                        <option value="false" <?=(($d->pep == 'false')?'selected':false)?>>Não</option>
                        <option value="true" <?=(($d->pep == 'true')?'selected':false)?>>Sim</option>
                    </select>
                    <div class="form-text"></div>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-label" for="naturalness">Naturalidade*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="naturalness" id="naturalness" class="form-control" placeholder="Naturalidade" value="<?=$d->naturalness?>">
                    <div class="form-text"></div>
                </div>



</div>

<div class="row">
                
                <div class="form-group col-md-8">
                    <label class="form-label" for="email">E-mail</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$d->email?>">
                    <div class="form-text"></div>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-label" for="phoneNumber">Telefone*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" 
                    acao type="text" inputmode="numeric" acao id="phoneNumber" class="form-control" placeholder="Telefone" value="<?=$d->phoneNumber?>">
                    <div class="form-text"></div>
                </div>

 </div>

    
 <div class="row">
                <div class="form-group col-md-6">
                    <label class="form-label" for="motherName">Nome da Mãe*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="motherName" id="motherName" class="form-control" placeholder="Nome da Mãe" value="<?=$d->motherName?>">
                    <div class="form-text"></div>
                </div>


                <div class="form-group col-md-6">
                    <label class="form-label" for="fatherName">Nome do Pai</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" type="text" name="fatherName" id="fatherName" class="form-control" placeholder="Nome do Pai" value="<?=$d->fatherName?>">
                    <div class="form-text"></div>
                </div>

   </div>

   

                <h3 style="margin-top:10px;
                border-bottom: 2px solid #174ba7;border-radius: 8px;
                padding: 2px 10px;color: #174ba7;margin-bottom: 15px">Documentação</h3>
                
 <div class="row">
                <div class="form-group col-md-4">
                    <label class="form-label" for="document_type">Tipo de Documento*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="document_type" id="document_type" class="form-select">
                        <option value="">:: Selecione ::</option>
                        <option value="rg" <?=(($d->document_type == 'rg')?'selected':false)?>>RG</option>
                        <option value="cnh" <?=(($d->document_type == 'cnh')?'selected':false)?>>CNH</option>
                    </select>
                    <div class="form-text"></div>
                </div>


                <div class="form-group col-md-8">
                    <label class="form-label" for="document_number">Número do Documento*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="document_number" id="document_number" class="form-control" placeholder="Número do Documento" value="<?=$d->document_number?>">
                    <div class="form-text"></div>
                </div>

</div>

<div class="row">
                <div class="form-group col-md-4">
                    <label class="form-label" for="document_issuingState">Origem do Doc*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="document_issuingState" id="document_issuingState" class="form-select">
                        <option value="">:: Selecione o estado ::</option>
                        <?php
                        foreach($siglas as $i => $sigla){
                        ?>
                        <option value="<?=$sigla?>" <?=(($d->document_issuingState == $sigla)?'selected':false)?>><?=$sigla?></option>
                        <?php
                        }
                        ?>
                    </select>    
                    <div class="form-text"></div>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-label" for="document_issuingAuthority">Orgão Emissor*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="document_issuingAuthority" id="document_issuingAuthority" class="form-select">
                        <option value="">:: Selecione ::</option>
                        <?php
                        $orgaos = ['SSP', 'IFP', 'IPF', 'PC', 'PF', 'SPTC', 'DPF', 'SJS', 'SDS', 'DETRAN', 'SDGPC'];
                        foreach($orgaos as $i => $sigla){
                        ?>
                        <option value="<?=$sigla?>" <?=(($d->document_issuingAuthority == $sigla)?'selected':false)?>><?=$sigla?></option>
                        <?php
                        }
                        ?>
                    </select> 
                    <div class="form-text"></div>
                </div>

                <div class="form-group col-md-4">
                    <label class="form-label" for="document_issueDate">Data da Emissão*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" tipo="data" name="document_issueDate" id="document_issueDate" class="form-control" placeholder="Data da Emissão" value="<?=dataBr(trim($d->document_issueDate))?>">
                    <div  class="form-text"></div>
                </div>
                
</div>



               

                <h3 style="margin-top:10px;
                border-bottom: 2px solid #174ba7;border-radius: 8px;
                padding: 2px 10px;color: #174ba7;margin-bottom: 15px">Endereço</h3>

<div class="row">
                <div class="form-group col-md-4">
                    <label class="form-label" for="address_zipCode">CEP*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="address_zipCode" id="address_zipCode" class="form-control" placeholder="CEP" value="<?=$d->address_zipCode?>">
                    <div class="form-text"></div>
                </div>


                <div class="form-group col-md-8">
                    <label class="form-label" for="address_street">Logradouro*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="address_street" id="address_street" class="form-control" placeholder="Avenida, rua ou Beco" value="<?=$d->address_street?>">
                    <div  class="form-text"></div>
                </div>

</div>

<div class="row">
                <div class="form-group col-md-3">
                    <label class="form-label" for="address_number">Número*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="address_number" id="address_number" class="form-control" placeholder="Número da Moradia" value="<?=$d->address_number?>">
                    <div  class="form-text"></div>
                </div>

                <div class="form-group col-md-9">
                    <label class="form-label" for="address_complement">Complemento</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" type="text" name="address_complement" id="address_complement" class="form-control" placeholder="Conjunto, Edifício, Condomínio, Bloco" value="<?=$d->address_complement?>">
                    <div class="form-text"></div>
                </div>

</div>
              
<div class="row">

                <div class="form-group col-md-4">
                    <label class="form-label" for="address_neighborhood">Bairro</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="address_neighborhood" id="address_neighborhood" class="form-control" placeholder="Bairro" value="<?=$d->address_neighborhood?>">
                    <div  class="form-text"></div>
                </div>


                <div class="form-group col-md-5">
                    <label class="form-label" for="address_city">Cidade*</label>
                    <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="address_city" id="address_city" class="form-control" placeholder="Cidade" value="<?=$d->address_city?>">
                    <div  class="form-text"></div>
                </div>


                <div class="form-group col-md-3">
                    <label class="form-label" for="address_state">Estado*</label>
                    <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="address_state" id="address_state" class="form-select">
                        <option value="">:: Selecione o Estado ::</option>
                        <?php
                        foreach($siglas as $i => $sigla){
                        ?>
                        <option value="<?=$sigla?>" <?=(($d->address_state == $sigla)?'selected':false)?>><?=$sigla?></option>
                        <?php
                        }
                        ?>
                    </select>   
                    <div  class="form-text"></div>
                </div>

</div>


                <!-- <div class="mb-3">
                    <label class="form-label" for="renda">Renda*</label>
                    <input acao type="text" name="renda" id="renda" class="form-control" placeholder="Cidade" value="<?=$d->renda?>">
                    <div  class="form-text"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="valor_patrimonio">Valor do Patrimônio*</label>
                    <input acao type="text" name="valor_patrimonio" id="valor_patrimonio" class="form-control" placeholder="Cidade" value="<?=$d->valor_patrimonio?>">
                    <div  class="form-text"></div>
                </div> -->
                
                <!-- <div class="mb-3">
                    <label class="form-label" for="cliente_iletrado_impossibilitado">Cliente Iletrado Impossibilitado*</label>
                    <select acao name="cliente_iletrado_impossibilitado" id="cliente_iletrado_impossibilitado" class="form-select">
                        <option value="">:: Selecione ::</option>
                        <option value="nao" <?=(($d->cliente_iletrado_impossibilitado == 'nao')?'selected':false)?>>Não</option>
                        <option value="sim" <?=(($d->cliente_iletrado_impossibilitado == 'sim')?'selected':false)?>>Sim</option>
                    </select>
                    <div  class="form-text"></div>
                </div> -->



                <h3 style="margin-top:10px;
                border-bottom: 2px solid #174ba7;border-radius: 8px;
                padding: 2px 10px;color: #174ba7;margin-bottom: 15px">Dados Bancários</h3>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-start">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="dados_bancarios" id="forma_pix" value="pix" <?=(($d->pixKey)?'checked':false)?>>
                                <label class="form-check-label" for="forma_pix">
                                    PIX
                                </label>
                            </div>
                            <div class="form-check ms-3">
                                <input class="form-check-input" type="radio" name="dados_bancarios" id="forma_conta" value="conta" <?=((!$d->pixKey)?'checked':false)?>>
                                <label class="form-check-label" for="forma_conta">
                                    Dados bancários
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="divConta" style="display:<?=(($d->pixKey)?'none':'block')?>; width:100%; margin-top:10px;">

                    <div class="row">               
                        <div class="form-group col-md-8">
                            <label class="form-label" for="bankCode">Banco*</label>
                            <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="bankCode" id="bankCode" class="form-select">
                                <option value="">:: Selecione o Banco ::</option>
                                <?php
                                arsort($banco);
                                foreach($bancos as $cod => $banco){
                                ?>
                                <option value="<?=$banco->value?>" <?=(($d->bankCode == $banco->value)?'selected':false)?>><?="{$banco->value} - {$banco->label}"?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <div  class="form-text"></div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="form-label" for="accountType">Tipo da Conta*</label>
                            <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="accountType" id="accountType" class="form-select">
                                <option value="">:: Selecione ::</option>
                                <option value="corrente" <?=(($d->accountType == 'corrente')?'selected':false)?>>Corrente</option>
                                <option value="poupanca" <?=(($d->accountType == 'poupanca')?'selected':false)?>>Poupança</option>
                            </select>
                            <div  class="form-text"></div>
                        </div>
                    </div>
                

                    <div class="row">  
                        <div class="form-group col-md-6">
                            <label class="form-label" for="accountNumber">Número da Conta*</label>
                            <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="accountNumber" id="accountNumber" class="form-control" placeholder="Número da Conta" value="<?=$d->accountNumber?>">
                            <div class="form-text"></div>
                        </div>


                        <div class="form-group col-md-2">
                            <label class="form-label" for="accountDigit">Dígito*</label>
                            <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="accountDigit" id="accountDigit" class="form-control" placeholder="Dígito da Conta" value="<?=$d->accountDigit?>">
                            <div  class="form-text"></div>
                        </div>


                        <div class="form-group col-md-4">
                            <label class="form-label" for="branchNumber">Agência*</label>
                            <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="branchNumber" id="branchNumber" class="form-control" placeholder="Agência" value="<?=$d->branchNumber?>">
                            <div class="form-text"></div>
                        </div>

                    </div>
                </div>

                <div class="divPix" style="display:<?=((!$d->pixKey)?'none':'block')?>; width:100%; margin-top:10px;">
                    <div class="row">               
                        <div class="form-group col-md-6">
                            <label class="form-label" for="pixKey">Chave PIX*</label>
                            <input style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao type="text" name="pixKey" id="pixKey" class="form-control" placeholder="Chave Pix" value="<?=$d->pixKey?>">
                            <div class="form-text"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label" for="pixKeyType">Tipo do PIX*</label>
                            <select style="border-radius:20px;box-shadow: 0px 0px 2px 1px #7e97c5;padding:12px" acao name="pixKeyType" id="pixKeyType" class="form-select">
                                <option value="">:: Selecione ::</option>
                                <?php
                                $tiposPix = ["CHAVE_ALEATORIA", "EMAIL", "TELEFONE", "CPF"];
                                foreach($tiposPix as $i => $sigla){
                                ?>
                                <option value="<?=$sigla?>" <?=(($d->pixKeyType == $sigla)?'selected':false)?>><?=$sigla?></option>
                                <?php
                                }
                                ?>
                            </select> 
                            <div class="form-text"></div>
                        </div>
                    </div>
                </div>





        <div class="mt-2" style="text-align:center">
            <a class="btn btn-primary btn-lg botao-capital"  href="#" local="fgts/saldo.php"><i class="fa-solid fa-angles-right"></i> Contratar FGTS</a>
        </div>

      



        </div>

        <div class="mt-3 text-end">
            <a class="text-danger text-decoration-none sair" style="cursor:pointer"><i class="fa-solid fa-right-from-bracket"></i> Sair do login</a>
        </div>

    </div>
    </div>
</div>
</div>
</div>

<script>
    $(function(){

        Carregando('none');

        $("#cpf").mask("999.999.999-99");
        $("#phoneNumber").mask("(99) 99999-9999");
        $("#birthdate, #document_issueDate").mask("99/99/9999");
        $("#address_zipCode").mask("99999-999");


        <?php 
            if($d->pixKey){
        ?>
            $("#bankCode").removeAttr("acao");
            $("#accountType").removeAttr("acao");
            $("#accountNumber").removeAttr("acao");
            $("#accountDigit").removeAttr("acao");
            $("#branchNumber").removeAttr("acao");

            $("#pixKey").attr("acao","");
            $("#pixKeyType").attr("acao","");

        <?php
            }else{
        ?>
            $("#bankCode").attr("acao","");
            $("#accountType").attr("acao","");
            $("#accountNumber").attr("acao","");
            $("#accountDigit").attr("acao","");
            $("#branchNumber").attr("acao","");
            $("#pixKey").removeAttr("acao");
            $("#pixKeyType").removeAttr("acao");
        <?php
            }
        ?>

        $("#forma_pix").click(function(){
            $(".divConta").css("display","none");
            $(".divPix").css("display","block");

            $("#bankCode").removeAttr("acao");
            $("#accountType").removeAttr("acao");
            $("#accountNumber").removeAttr("acao");
            $("#accountDigit").removeAttr("acao");
            $("#branchNumber").removeAttr("acao");

            $("#pixKey").attr("acao","");
            $("#pixKeyType").attr("acao","");

            $("input[acao]").off('blur').on('blur',function(){
                mudanca($(this));
            })

            $("select[acao]").off('change').on('change', function(){
                mudanca($(this));
            })

        })

        $("#forma_conta").click(function(){

            $(".divConta").css("display","block");
            $(".divPix").css("display","none");

            $("#bankCode").attr("acao","");
            $("#accountType").attr("acao","");
            $("#accountNumber").attr("acao","");
            $("#accountDigit").attr("acao","");
            $("#branchNumber").attr("acao","");
            $("#pixKey").removeAttr("acao");
            $("#pixKeyType").removeAttr("acao");

            $("input[acao]").off('blur').on('blur',function(){
                mudanca($(this));
            })

            $("select[acao]").off('change').on('change', function(){
                mudanca($(this));
            })


        })

        <?php

        include("barra_status.php");
 
        if($_SESSION['codUsr']){
        ?>
        localStorage.setItem("codUsr", '<?=$_SESSION['codUsr']?>');
        $.ajax({
            url:"fgts/sessao.php",
            type:"POST",
            data:{
                codUsr:'<?=$_SESSION['codUsr']?>'
            },
            success:function(dados){
                // $(".palco").html(dados);
                console.log(dados)
            }
        })
        <?php
        }
        ?>

        function preenchimento(){
            campos = [];
            conteudo = [];
            pendentes = [];
            $("input[acao]").each(function(){
                valor = $(this).val(); 
                if(valor){
                    conteudo.push(valor);
                }else{
                    pendentes.push($(this).parent("div").children("label").text());
                }
                campos.push($(this).attr("id"));
            })

            $("select[acao]").each(function(){
                valor = $(this).val(); 
                if(valor){
                    conteudo.push(valor);
                }else{
                    pendentes.push($(this).parent("div").children("label").text());
                }
                campos.push($(this).attr("id"));
            })

            qtcp = campos.length;
            qtct = conteudo.length;

            pct = ((qtct*100)/qtcp).toFixed(0);

            //style="width: 10%" aria-valuenow="10"
            $("#progresso").attr("aria-valuenow", pct);
            $("#progresso").html(`${pct}%`);
            $("#progresso").css("width", `${pct}%`);


            $.ajax({
                url:"fgts/cadastro.php",
                type:"POST",
                data:{
                    cadastro_percentual:pct,
                    acao:'cadastro_percentual',
                    campos_pendentes:pendentes
                },
                success:function(dados){
                    
                }
            })


            console.log(`Temos ${qtct} do total de ${qtcp}`)

        }

        preenchimento();


        function mudanca(obj){

            campo = obj.attr("id");
            valor = obj.val();
            tipo = obj.attr("tipo");
            if(campo == 'cpf'){

                if(!validarCPF(valor)){
                    $.alert({
                        title:"Erro CPF",
                        content:"O CPF Informado não é válido!",
                        type:'red'
                    })
                    $("#cpf").val('');
                    return false;
                }
            }   


            $.ajax({
                url:"fgts/cadastro.php",
                type:"POST",
                data:{
                    campo,
                    valor,
                    tipo,
                    acao:'salvar'
                },
                success:function(dados){
                    console.log(dados)
                    if(dados == 'error'){
                        $.alert({
                            title: "Erro CPF",
                            content:"O CPF Informado Já encontra-se cadastrado!",
                            type:'red'
                        })
                        $("#cpf").val('');
                    }

                    preenchimento();
                }
            })
        }

        $("input[acao]").off('blur').on('blur',function(){
            mudanca($(this));
        })

        $("select[acao]").off('change').on('change', function(){
            mudanca($(this));
        })

        $(".sair").click(function(){
            telefone = $("#telefone").val();
            
            $.confirm({
                title:"Sair do Login",
                content:'Deseja realmente sair do login da sua área restrita?',
                type:'orange',
                buttons:{
                    'Sim':{
                        text:'SIM',
                        btnClass:'btn btn-danger btn-sm',
                        action:function(){
                            localStorage.removeItem("codUsr");
                            $.ajax({
                                url:"fgts/sessao.php",
                                data:{
                                    codUsr:''
                                },
                                type:"POST",
                                success:function(dados){

                                    $.ajax({
                                        url:"fgts/home.php?wapp=<?=$_SESSION['wapp']?>",
                                        success:function(dados){
                                            $(".palco").html(dados);
                                        }
                                    })

                                }
                            });


                        }
                    },
                    'não':{
                        text:'NÃO',
                        btnClass:'btn btn-primary btn-sm',
                        action:function(){

                        }
                    },
                    
                }
            })

            


        })

        $("a[local]").click(function(){
            Carregando();
            url = $(this).attr("local");
            preenchimento();
            //*/
            setTimeout(() => {

                if($(`div[aria-valuenow]`).attr("aria-valuenow") != 100){
                    Carregando('none')
                    $.alert({
                        type:"red",
                        title:"Erro Cadastro",
                        content:"Para prosseguir é necessário o preenchimento do formulário completo."
                    });
                    return false;
                }              
                $.ajax({
                    url,
                    type:"POST",
                    data:{
                         codUsr:'<?=$_SESSION['codUsr']?>'
                    },
                    success:function(dados){
                        Carregando('none');
                        // localStorage.removeItem("codUsr");
                        // $.alert({
                        //     type:"green",
                        //     title:"Cadastro Confirmado",
                        //     content:"Seu cadastro está confirmado, em breve um dos nossos atendentes entrará em contato!"
                        // });  
                        $(".palco").html(dados);
                    }
                })  
                              
            }, 2000);
            //*/

        })

        $.ajax({
            url:"assets/lib/log_acessos.php",
            success:function(dados){
            //Retorno da função
            // console.log(dados);
            }
        });


    })
</script>