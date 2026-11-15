<?php
    include_once "../class/conexao.class.php";
    include_once "../seguranca.php";

    $act = strtoupper($_GET['act']);
    $conexao = new Conexao();

    switch($act){
        case "LISTA_TABELAS":
            // Lista apenas tabelas (exclui views)
            $link = $conexao->conecta();
            
            // Obtém o nome do banco de dados atual
            $query = "SELECT DATABASE() as db_name";
            $res = $conexao->pesquisa($query);
            $row = mysqli_fetch_assoc($res);
            $nomeBanco = mysqli_real_escape_string($link, $row['db_name']);
            
            // Lista apenas tabelas (BASE TABLE), excluindo views
            $query = "SELECT TABLE_NAME 
                      FROM information_schema.TABLES 
                      WHERE TABLE_SCHEMA = '" . $nomeBanco . "' 
                      AND TABLE_TYPE = 'BASE TABLE'
                      ORDER BY TABLE_NAME";
            $res = $conexao->pesquisa($query);
            $contador = 0;
            while($row = mysqli_fetch_array($res)){
                $nomeTabela = $row['TABLE_NAME'];
                $contador++;
                ?>
                <tr
                    onclick='javascript:selecionarTabela("<?=$nomeTabela;?>")'
                    class='lista_formularios'
                    style="cursor: pointer;">
                    <td>
                        <?
                            echo $nomeTabela;
                        ?>
                    </td>
                </tr>
                <?php
            }
            if($contador == 0){
                ?>
                <tr>
                    <td style="text-align: center;">Nenhuma tabela encontrada</td>
                </tr>
                <?php
            }
        break;

        case "LISTA_CAMPOS":
            $tabela = $_GET['tabela'];
            if(empty($tabela)){
                echo "<tr><td colspan='4' style='text-align: center;'>Tabela nÃƒÆ’Ã‚Â£o especificada</td></tr>";
                break;
            }

            // Lista todos os campos da tabela selecionada
            $query = "SHOW COLUMNS FROM `" . mysqli_real_escape_string($conexao->conecta(), $tabela) . "`";
            $res = $conexao->pesquisa($query);
            $temCampos = false;
            while($row = mysqli_fetch_assoc($res)){
                $temCampos = true;
                $nomeCampo = $row['Field'];
                $tipoCampo = $row['Type'];
                $nulo = $row['Null'] == 'YES' ? 'Sim' : 'NÃƒÆ’Ã‚Â£o';
                $chave = '';
                if($row['Key'] == 'PRI'){
                    $chave = 'PRIMARY';
                } elseif($row['Key'] == 'UNI'){
                    $chave = 'UNIQUE';
                } elseif($row['Key'] == 'MUL'){
                    $chave = 'INDEX';
                }
                ?>
                <tr>
                    <td><?=$nomeCampo;?></td>
                    <td><?=$tipoCampo;?></td>
                    <td><?=$nulo;?></td>
                    <td><?=$chave;?></td>
                </tr>
                <?php
            }
            if(!$temCampos){
                echo "<tr><td colspan='4' style='text-align: center;'>Nenhum campo encontrado</td></tr>";
            }
        break;

        case "CRIAR_CAMPO":
            $tabela = $_GET['tabela'];
            if(empty($tabela)){
                $resp = array(
                    "resultado" => "ERRO",
                    "mensagem" => "Tabela nÃƒÆ’Ã‚Â£o especificada"
                );
                echo json_encode($resp);
                break;
            }

            // Verifica se o campo jÃƒÆ’Ã‚Â¡ existe
            $query = "SHOW COLUMNS FROM `" . mysqli_real_escape_string($conexao->conecta(), $tabela) . "` LIKE 'log_usuario'";
            $res = $conexao->pesquisa($query);
            
            if(mysqli_num_rows($res) > 0){
                $resp = array(
                    "resultado" => "ERRO",
                    "mensagem" => "O campo log_usuario jÃƒÆ’Ã‚Â¡ existe nesta tabela"
                );
                echo json_encode($resp);
                break;
            }

            // Cria o campo log_usuario
            $query = "ALTER TABLE `" . mysqli_real_escape_string($conexao->conecta(), $tabela) . "` ADD COLUMN `log_usuario` INT(11) NULL";
            $res = $conexao->pesquisa($query);
            
            if($res){
                $resp = array(
                    "resultado" => "OK",
                    "mensagem" => "Campo log_usuario criado com sucesso na tabela " . $tabela
                );
            } else {
                $resp = array(
                    "resultado" => "ERRO",
                    "mensagem" => "Erro ao criar campo: " . mysqli_error($conexao->conecta())
                );
            }
            echo json_encode($resp);
        break;

        case "CRIAR_TRIGGERS":
            $tabela = $_GET['tabela'];
            if(empty($tabela)){
                $resp = array(
                    "resultado" => "ERRO",
                    "mensagem" => "Tabela nÃƒÆ’Ã‚Â£o especificada"
                );
                echo json_encode($resp);
                break;
            }

            // Verifica se a tabela log_alteracoes existe, se nÃƒÆ’Ã‚Â£o, cria
            $query = "SHOW TABLES LIKE 'log_alteracoes'";
            $res = $conexao->pesquisa($query);
            if(mysqli_num_rows($res) == 0){
                // Cria a tabela log_alteracoes se nÃƒÆ’Ã‚Â£o existir
                $query = "CREATE TABLE IF NOT EXISTS `log_alteracoes` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `tabela` VARCHAR(100) NOT NULL,
                    `id_tabela` INT(11) NULL,
                    `campo` VARCHAR(100) NOT NULL,
                    `id_campo` INT(11) NULL,
                    `acao` VARCHAR(1) NULL,
                    `usuario` INT(11) NULL,
                    `usuario_nome` VARCHAR(255) NULL,
                    `data_hora` DATETIME NULL,
                    `data_hora_lis` VARCHAR(14) NULL,
                    `antigo` TEXT NULL,
                    `novo` TEXT NULL,
                    `motivo_alteracao` TEXT NULL,
                    PRIMARY KEY (`id`),
                    INDEX `idx_tabela_id` (`tabela`, `id_tabela`),
                    INDEX `idx_data_hora` (`data_hora`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
                $conexao->pesquisa($query);
            }

            // Busca todos os campos da tabela (exceto log_usuario)
            $query = "SHOW COLUMNS FROM `" . mysqli_real_escape_string($conexao->conecta(), $tabela) . "`";
            $res = $conexao->pesquisa($query);
            $campos = array();
            while($row = mysqli_fetch_assoc($res)){
                if($row['Field'] != 'log_usuario'){
                    $campos[] = $row['Field'];
                }
            }

            if(empty($campos)){
                $resp = array(
                    "resultado" => "ERRO",
                    "mensagem" => "Nenhum campo encontrado para criar triggers (exceto log_usuario)"
                );
                echo json_encode($resp);
                break;
            }

            $link = $conexao->conecta();
            // Verifica se a tabela tem campo 'id' para usar como id_tabela
            $query = "SHOW COLUMNS FROM `" . mysqli_real_escape_string($link, $tabela) . "` LIKE 'id'";
            $res = $conexao->pesquisa($query);
            $temCampoId = mysqli_num_rows($res) > 0;

            $triggersCriados = 0;
            $erros = array();

            foreach($campos as $campo){
                $nomeCampoEscapado = mysqli_real_escape_string($link, $campo);
                $nomeTabelaEscapado = mysqli_real_escape_string($link, $tabela);
                
                // Nome do trigger: trg_audit_[tabela]_[campo]_update (limitado a 64 caracteres)
                $nomeTrigger = "trg_audit_" . substr(str_replace(" ", "_", $tabela), 0, 30) . "_" . substr(str_replace(" ", "_", $campo), 0, 20) . "_upd";
                if(strlen($nomeTrigger) > 64){
                    $nomeTrigger = substr($nomeTrigger, 0, 64);
                }
                
                // Remove trigger existente se houver
                $query = "DROP TRIGGER IF EXISTS `" . mysqli_real_escape_string($link, $nomeTrigger) . "`";
                $conexao->pesquisa($query);

                // Prepara o valor para id_tabela
                $valorIdTabela = $temCampoId ? "NEW.id" : "NULL";

                // Cria o trigger
                // O trigger verifica se o valor novo ÃƒÂ© diferente do valor antigo
                // Usa COALESCE para tratar valores NULL corretamente
                $query = "CREATE TRIGGER `" . mysqli_real_escape_string($link, $nomeTrigger) . "`
                    AFTER UPDATE ON `" . $nomeTabelaEscapado . "`
                    FOR EACH ROW
                    BEGIN
                        IF (COALESCE(OLD.`" . $nomeCampoEscapado . "`, '') != COALESCE(NEW.`" . $nomeCampoEscapado . "`, '')) THEN
                            INSERT INTO log_alteracoes (
                                tabela,
                                id_tabela,
                                campo,
                                usuario,
                                data_hora,
                                data_hora_lis,
                                antigo,
                                novo,
                                acao
                            ) VALUES (
                                '" . $nomeTabelaEscapado . "',
                                " . $valorIdTabela . ",
                                '" . $nomeCampoEscapado . "',
                                COALESCE(NEW.log_usuario, NULL),
                                NOW(),
                                DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'),
                                COALESCE(OLD.`" . $nomeCampoEscapado . "`, ''),
                                COALESCE(NEW.`" . $nomeCampoEscapado . "`, ''),
                                'A'
                            );
                        END IF;
                    END";

                $res = $conexao->pesquisa($query);
                if($res){
                    $triggersCriados++;
                } else {
                    $erroMsg = mysqli_error($link);
                    $erros[] = "Erro ao criar trigger para campo " . $campo . ": " . ($erroMsg ? $erroMsg : "Erro desconhecido");
                }
            }

            if($triggersCriados > 0){
                $mensagem = "Triggers criados com sucesso!\n\nTotal de triggers criados: " . $triggersCriados;
                if(!empty($erros)){
                    $mensagem .= "\n\nErros encontrados:\n" . implode("\n", $erros);
                }
                $resp = array(
                    "resultado" => "OK",
                    "mensagem" => $mensagem
                );
            } else {
                $resp = array(
                    "resultado" => "ERRO",
                    "mensagem" => "Nenhum trigger foi criado. Erros:\n" . implode("\n", $erros)
                );
            }
            echo json_encode($resp);
        break;

        default:
            echo "AÃƒÆ’Ã‚Â§ÃƒÆ’Ã‚Â£o nÃƒÆ’Ã‚Â£o reconhecida";
        break;
    }
?>
