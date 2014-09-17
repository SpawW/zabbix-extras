#!/bin/bash
# Autor: Adail Horst
# Email: the.spaww@gmail.com

INSTALAR="N";
AUTOR="the.spaww@gmail.com";
TMP_DIR="/tmp/upgZabbix";
VERSAO_INST="2.1-RC3";
UPDATEBD="S";
BRANCH="Zabbix-Extras-2.0.1";

registra() {
    [ -d ${TMP_DIR} ] || mkdir ${TMP_DIR}
    echo $(date)" - $1" >> $TMP_DIR/logInstall.log; 
    echo "-->Mensagem $1";
}
installMgs() {
    if [ "$1" = "U" ]; then
        tipo="Upgrade";
    else
        tipo="Clean";
    fi
    registra " $tipo install ($2)...";
}


downloadFiles() {
    dialog \
        --title 'Download Files'        \
        --radiolist "$M_PATCH"  \
        0 0 0                                    \
        S   "$M_DOWNLOAD_FILES_SIM"  on    \
        N   "$M_DOWNLOAD_FILES_NAO"  off   \
        2> $TMP_DIR/resposta_dialog.txt
    DOWNLOADFILES=`cat $TMP_DIR/resposta_dialog.txt `;
    registra " Baixar Arquivos [$DOWNLOADFILES] ";
}

downloadPackage() {
    ARQ_TMP="$1";
    REPOS="$2";
    if [ "$DOWNLOADFILES" = "S" ]; then
        if [ -f $ARQ_TMP ]; then
            rm $ARQ_TMP;
        fi
        # Baixa repositorio
        wget $REPOS -O $ARQ_TMP --no-check-certificate;
    fi
}

unzipPackage() {
    ARQ_TMP="$1";
    DIR_TMP="$2";
    DIR_DEST="$3";
    
    cd /tmp;
    # Descompacta em TMP
    if [ -e $DIR_TMP ]; then
        unalias rm;
        rm -rf $DIR_TMP;
    fi
    unzip $ARQ_TMP;
    cd $DIR_TMP
    if [ ! -e "$DIR_DEST" ]; then
        mkdir -p "$DIR_DEST";
    fi
}

recriaTabelas() {
    dialog \
        --title 'Zabbix Extras BD Update ['$VERSAO_INST']'        \
        --radiolist "$M_UPGRADE_BD"  \
        0 0 0                                    \
        S   "$M_UPGRADE_BD_SIM"  on    \
        N   "$M_UPGRADE_BD_NAO"  off   \
        2> $TMP_DIR/resposta_dialog.txt
    UPDATEBD=`cat $TMP_DIR/resposta_dialog.txt `;
    registra " Recriar banco [$UPDATEBD] ";
}
idioma() {
    # Selecao de Idioma -------------------------------------------------------------------------
    if [ -d $TMP_DIR ]; then
        if [ -f $TMP_DIR/resposta_dialog.txt ]; then
            rm $TMP_DIR/resposta_dialog.txt;
        fi
    else
        mkdir $TMP_DIR;
    fi
    dialog \
        --title 'Zabbix Extras Installer ['$VERSAO_INST']'        \
        --radiolist 'Informe o idioma (Enter the language for the installer) '  \
        0 0 0                                    \
        pt   'Portugues / Brasil'  on    \
        en   'English'   off   \
        2> $TMP_DIR/resposta_dialog.txt
    OPCOES=`cat $TMP_DIR/resposta_dialog.txt `;
    if [ "`echo $OPCOES| wc -m`" -eq 3 ]; then
        INSTALAR="S";
    else
        echo $OPCOES| wc -m
        registra "Instalacao abortada ($OPCOES)...";
        exit;
    fi
    case $OPCOES in
	"pt" )
      M_BASE="Este instalador ira adicionar um menu extra ao final da barra de menus do seu ambiente. Para a correta instalacao sao necessarios alguns parametros.";
      M_CAMINHO="Favor informar o caminho para o frontend do zabbix";
      M_BASE_PHP="Este instalador ira configurar a diretiva do PHP: short_open_tag, ativando-a. Este passo é necessário para instalar o ZabTree e ZabGeo.";
      M_CAMINHO_PHP="Favor informar o caminho para o arquivo php.ini";
      M_ERRO_CAMINHO_PHP="O php.ini nao foi encontrado no caminho informado."
      M_URL="Favor informar a URL do zabbix (usando localhost)";
      M_ERRO_CAMINHO="O caminho informado para o frontend do zabbix nao foi encontrado "
      M_ERRO_ABORT="Instalacao abortada!";
      M_PATCH="Efetuar download dos arquivos do patch (S)?";
      M_PATCH_CAMINHO="Favor informar caminho para os arquivos do patch";
      M_PATCH_ERRO="O arquivo de patch nao foi localizado no caminho informado";
      M_INSTALL_ALL="Selecione os modulos a instalar";
      M_ZABBIX_CAT="Instalar o modulo de Gestao de Capacidade.";
      M_ZABBIX_SC="Instalar o modulo de Gestao de Armazenamento.";
      M_ZABBIX_NS="Instalar o modulo de Relatorio de itens nao suportados.";
      M_ZABBIX_EM="Instalar o modulo de Correlacionamento de eventos.";
      M_RESUMO_FRONT="Caminho do frontend: ";
      M_RESUMO_PATCH="Localizacao dos arquivos do patch: ";
      M_RESUMO_INSTALA="Confirma a instalacao nos moldes acima?";
      M_UPGRADE_BD="Foi detectada uma instalação anterior. Deseja SUBSTITUIR os dados das tabelas do ZE pelos novos ? Caso a instalação esteja danificada você deverá escolher esta opção!";
      M_UPGRADE_BD_SIM="Recriar tabelas zbxe";
      M_UPGRADE_BD_NAO="Manter tabelas zbxe existentes";
      M_DOWNLOAD_FILES_SIM="Baixar os arquivos mais atuais (recomendado)";
      M_DOWNLOAD_FILES_NAO="Utilizar os arquivos baixados e salvos manualmente em /tmp";
      M_ERRO_DISTRO="Distribucao nao prevista ($LINUX_DISTRO)... favor contactar ";
      M_DISTRO_SIM="SIM, continue mesmo sem o suporte a instalacao de pacotes (necessario wget, dialog e unzip).";
      M_DISTRO_NAO="NAO, aborte a instalacao.";
            ;;
	*) 
      M_BASE="This installer will add an extra menu to the end of the menu bar of your environment. For installation are needed to inform some parameters.";
      M_CAMINHO="Please enter the path to the zabbix frontend ";
      M_BASE_PHP="This installer will configure the PHP: short_open_tag, activating it. This step is required to install and ZabTree ZabGeo.";
      M_CAMINHO_PHP="Please enter the path to php.ini.";
      M_ERRO_CAMINHO_PHP="The php.ini file was not found in the path provided."
      M_URL="Please enter the URL to the zabbix frontend (using localhost)";
      M_ERRO_CAMINHO="The informed path to zabbix frontend is not valid "
      M_ERRO_ABORT="Install aborted!";
      M_PATCH="Download the patch files (S) (S = Yes)?";
      M_PATCH_CAMINHO="Please inform the path to patch files";
      M_PATCH_ERRO="The patch file not found on informed path";
      M_INSTALL_ALL="Select the available menu items";
      M_ZABBIX_CAT="Install Capacity and Trends.";
      M_ZABBIX_SC="Install Storage Costs.";
      M_ZABBIX_NS="Install Not Supported Itens Report.";
      M_ZABBIX_NS="Install Event Management.";
      M_RESUMO_FRONT="Path to the Zabbix frontend: ";
      M_RESUMO_PATCH="Path to patch files: ";
      M_RESUMO_INSTALA="Confirm installation?";
      M_UPGRADE_BD="A previous installation was detected. Do you want to REPLACE the data from the tables by the new ZBXE data? If the installation is damaged you must choose this option!";
      M_UPGRADE_BD_SIM="Re-create zbxe tables";
      M_UPGRADE_BD_NAO="Preserve zbxe tables";
      M_DOWNLOAD_FILES_SIM="Get from internet latest version of patchs (recomended)";
      M_DOWNLOAD_FILES_NAO="Use files saved in /tmp";
      M_ERRO_DISTRO="Unkown linux version ($LINUX_DISTRO)... please contact for support: ";
      M_DISTRO_SIM="YES, continue without support to install OS packages (required wget, dialog and unzip) (S = YES).";
      M_DISTRO_NAO="NO, stop install.";
        ;;
    esac
}
# Pre-requisitos para o funcionamento do instalador ============================
preReq() {
    # Verificando e instalando o wget
    RESULT=`which wget 2>&-  | wc -l`;
    if [ "$RESULT" -eq 0 ]; then
        registra "Instalando wget (pre requisito para todo o processo)";
        instalaPacote "wget";
    fi
    # Verificando e instalando o dialog
    if [ `which dialog 2>&-  | wc -l` -eq 0 ]; then
        registra "Instalando dialog (pre requisito para todo o processo)";
        instalaPacote "dialog";
    fi
    # Verificando e instalando o unzip
    if [ `which unzip 2>&-  | wc -l` -eq 0 ]; then
        registra "Instalando unzip (pre requisito para todo o processo)";
        instalaPacote "unzip";
    fi
    # Verificando e instalando o php-curl
    if [ `which unzip 2>&-  | wc -l` -eq 0 ]; then
        registra "Instalando php-curl (pre requisito para o ZabGeo e ZabTree)";
        instalaPacote "php-curl php5-curl";
    fi
}
# Define os parametros especificos de cada distribuicao ========================
identificaDistro() {
    TMP=`cat  /etc/issue | head -n1 | tr "[:upper:]" "[:lower:]" | sed 's/release//g' | sed 's/  / /g' | sed 's/welcome\ to\ //g' `;
    LINUX_DISTRO=`echo $TMP | head -n1 | awk -F' ' '{print $1}'` ;
    LINUX_VER=`echo $TMP | sed 's/release//g' | awk -F' ' '{print $2}'`;
#    if [ -f /etc/redhat-release ]; then
    if [ -f /etc/redhat-release -o -f /etc/system-release ]; then
        PATHDEF="/var/www/html";
        GERENCIADOR_PACOTES='yum install -y ';
    else
        if [ `which zypper 2>&-  | wc -l` -eq 1 ]; then
            PATHDEF="/usr/share/zabbix";
            GERENCIADOR_PACOTES='zypper install -y ';
        else
            PATHDEF="/var/www";
            GERENCIADOR_PACOTES='apt-get install -y ';
        fi
    fi
    if [ -f /tmp/upgZabbix/logInstall.log ]; then
        TMP=`cat /tmp/upgZabbix/logInstall.log | grep "Path do frontend" | tail -n1 | awk -F[ '{print $2}' | awk -F] '{print $1}'`;
        if [ ! -z $TMP ]; then
            PATHDEF=$TMP;
        fi
    fi
    case $LINUX_DISTRO in
	"ubuntu" | "debian" | "red hat" | "red" | "centos" | "opensuse" | "opensuse" | "amazon" )
            CAMINHO_RCLOCAL="/etc/rc.local";
            registra "Versao do Linux - OK ($LINUX_DISTRO - $LINUX_VER)"
            ;;
	*) 
            echo "$M_ERRO_DISTRO Required: wget, unzip, dialog";
            dialog \
                --title 'Problem'        \
                --radiolist "$M_ERRO_DISTRO"  \
                0 0 0                                    \
                S   "$M_DISTRO_SIM"  on    \
                N   "$M_DISTRO_NAO"  off   \
                2> $TMP_DIR/resposta_dialog.txt
            CONTINUA=`cat $TMP_DIR/resposta_dialog.txt `;
            registra " Distribuicao nao prevista, continuar [$DOWNLOADFILES [$LINUX_DISTRO - $LINUX_VER] ";
            if [ "$CONTINUA" = "S" ]; then
                PATHDEF="/var/www";
                GERENCIADOR_PACOTES='echo ';
                CAMINHO_RCLOCAL="/etc/rc.local";
                $LINUX_DISTRO="OUTROS";
            else
                exit 1;
            fi
            #registra "Distribucao nao prevista ($LINUX_DISTRO)... favor contactar $AUTOR"; exit 1; 
        ;;
    esac
}
instalaPacote() {
    registra "============== Instalando pacote(s) ($1 $2 $3 $4 $5 $6 $7 $8 $9) =================";
    $GERENCIADOR_PACOTES $1 $2 $3 $4 $5 $6 $7 $8 $9  ${10} \
  ${11} ${12} ${13} ${14} ${15} ${16} ${17} ${18} ${19} ${20} \
  ${21} ${22} ${23} ${24} ${25} ${26} ${27} ${28} ${29} ${30};
}

caminhoFrontend() {
    dialog --inputbox "$M_BASE\n$M_CAMINHO" 0 0 "$PATHDEF" 2> $TMP_DIR/resposta_dialog.txt;
    CAMINHO_FRONTEND=`cat $TMP_DIR/resposta_dialog.txt`;
    if [ ! -d "$CAMINHO_FRONTEND" ]; then
        registra $M_ERRO_CAMINHO"($CAMINHO_FRONTEND). "$M_ERRO_ABORT;
        exit 0;
    fi
    cd $CAMINHO_FRONTEND;
    URLZABBIX="http://localhost/zabbix";
    if [ -f /tmp/upgZabbix/logInstall.log ]; then
        TMP=`cat /tmp/upgZabbix/logInstall.log | grep "URL do" | tail -n1 | awk -F[ '{print $2}' | awk -F] '{print $1}'`;
        if [ ! -z $TMP ]; then
            URLZABBIX=$TMP;
        fi
    fi
    dialog --inputbox "$M_BASE\n$M_URL" 0 0 $URLZABBIX 2> $TMP_DIR/resposta_dialog.txt;
    URL_FRONTEND=`cat $TMP_DIR/resposta_dialog.txt`;
}

instalaLiteral() {
    # Verificacao de instalacao previa do patch -- Menu.inc.php ------------
    registra "Instalando patch de literais...";
    ARQUIVO="include/func.inc.php";
    TAG_INICIO='\#\#Zabbix\-Extras-Literal';
    TAG_FINAL="$TAG_INICIO-FIM";
    cd $CAMINHO_FRONTEND;
    cp $ARQUIVO include/func.inc.php.original
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
      registra "Existe instalacao previa no arquivo... removendo customizacao do patch literal!";
      sed -i "$INIINST,$FIMINST d" $ARQUIVO
    fi
    registra "Instalando tags identificadoras do menu...";
    NUMLINHA=`cat $ARQUIVO | sed -ne "/\/\/ any other unit/{=;q;}"`;
    sed -i "$NUMLINHA i##Zabbix-Extras-Literal\n##Zabbix-Extras-Literal-FIM" $ARQUIVO
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    sed -i "$FIMINST i if(\$options['units'] == 'literal'){ return round(\$options['value'], ZBX_UNITS_ROUNDOFF_UPPER_LIMIT); }" $ARQUIVO
    FIMINST=$(($FIMINST+1));
}

corTituloMapa() {
    # Arquivo com as principais definicoes dos mapas ===========================
    ARQUIVO="include/classes/sysmaps/CMapPainter.php";
    # Substituindo a cor do titulo do mapa =====================================
    COR='blue';
    sed -i "s/'titleColor' => '.*'/'titleColor' => '#$COR'/" $ARQUIVO;
    # Removendo o titulo dos mapas =============================================
    ESCONDE='S';
    if ["$ESCONDE" -eq "S" ]; then
        EXTRA="#"; ESCONDE="#";
    else 
        EXTRA=""; ESCONDE="";
    fi
    sed -i "s/\$this->paintTitle\(\)\;/"$ESCONDE"\$this->paintTitle();/" $ARQUIVO;

    # Desabilita a borda do mapa ===============================================
    BORDA='false';
    sed -i "s/'border' => .*,/'border' => $BORDA,/" $ARQUIVO;
    # Define a cor de fundo do mapa ============================================
    CORFUNDO='false';
    sed -i "s/'bgColor' => '.*',/'bgColor' => '#$CORFUNDO',/" $ARQUIVO;
#'borderColor' => 'black'
    # Arquivo com as principais definicoes dos mapas ===========================
    ARQUIVO="include/classes/sysmaps/CCanvas.php";
    EMPRESA="SERPRO";
    TAMANHO=$((120+$(echo $EMPRESA | wc -c)*4));
 #$this->width - 120, $this->height - 12, $date
    sed -i "s/\$this->width - .*, \$this->height - 12, .*\$date/\$this->width - $TAMANHO, \$this->height - 12, '$EMPRESA '.\$date/" $ARQUIVO;
    # Tamanho da fonte do título dos elementos nos mapas =======================
    ARQUIVO="include/classes/sysmaps/CCanvas.php";
    EMPRESA="SERPRO";
    TAMANHO=$((120+$(echo $EMPRESA | wc -c)*4));
 #$this->width - 120, $this->height - 12, $date
    sed -i "s/\$this->width - .*, \$this->height - 12, .*\$date/\$this->width - $TAMANHO, \$this->height - 12, '$EMPRESA '.\$date/" $ARQUIVO;
}
# include/classes/sysmaps/CCanvas.php - linha 69
# include/classes/sysmaps/CMapPainter.php 
#  linha que printa o titulo: 79 
#  linha que define a cor do titulo do mapa 33

suporteBDCustom() {
    registra "Configurando suporte a customizacoes que usam banco de dados...";
    ARQUIVO="include/config.inc.php";
    TAG_INICIO='##Zabbix-Extras-BD-Support';
    NUMLINHA=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    if [ ! -z $NUMLINHA ]; then
        installMgs "U" "suporte bd"; #echo "--> Upgrade install...";
        recriaTabelas;
        sed -i "$NUMLINHA,$ d" $ARQUIVO
    else
        installMgs "N" "suporte bd"; #echo "--> Clean Install...";
    fi
    echo "$TAG_INICIO" >> $ARQUIVO;
    echo "require_once dirname(__FILE__).'/zbxe_visual_imp.php';" >> $ARQUIVO;
}
customMapas() {
    registra "Configurando suporte a customizacoes nos mapas...";
    ARQUIVO="include/classes/sysmaps/CMapPainter.php";
    TAG_INICIO='##Zabbix-Extras-map-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "cores mapa"; 
    else
        installMgs "N" "cores mapa"; 
        TMP='$this->options = array(';
        TMP_FIM="'drawAreas' => true";
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=`cat $ARQUIVO | sed -ne "/$TMP_FIM/{=;q;}"`;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="global \$ZBXE_VAR;\n\$this->options = array(\n'map' => array(\n  'bgColor' => 'red',";
    TXT_CUSTOM="$TXT_CUSTOM\n  'borderColor' => 'darkred', \n  'titleColor' => 'green',";
    TXT_CUSTOM="$TXT_CUSTOM\n  'border' => \$ZBXE_VAR['map_border_show'], 'drawAreas' => true";
# Mudado por conta da validacao de cores do modulo ...
#    TXT_CUSTOM="global \$ZBXE_VAR;\n\$this->options = array(\n'map' => array(\n  'bgColor' => \$ZBXE_VAR['map_background_color'],";
#    TXT_CUSTOM="$TXT_CUSTOM\n  'borderColor' => \$ZBXE_VAR['map_border_color'], \n  'titleColor' => \$ZBXE_VAR['map_title_color'],";
#    TXT_CUSTOM="$TXT_CUSTOM\n  'border' => \$ZBXE_VAR['map_border_show'], 'drawAreas' => true";
#
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO

    # ------- Modificacao no canvas para suportar as cores e o nome da empresa -
    ARQUIVO="include/classes/sysmaps/CCanvas.php";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "empresa"; 
    else
        installMgs "N" "empresa"; 
        TMP='imagestring($this->canvas';
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="global \$ZBXE_VAR;\n imagestring(\$this->canvas, 0, \$this->width - \$ZBXE_VAR['map_date_width'], \$this->height - 12, \$ZBXE_VAR[\"map_company\"].' '.\$date, \$this->getColor('darkgreen'));";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
# Adicao de funcao para calculo de cores personalizadas
    TAG_INICIO='##Zabbix-Extras-function-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "funcao adicional para cores"; 
    else
        installMgs "N" "funcao adicional para cores"; 
        TMP='class CCanvas {';
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="global \$ZBXE_VAR;\n";
    TXT_CUSTOM="$TXT_CUSTOM function zbxeHtmlColorToRGB(\$color){ \n\t \$hexcolor = str_split(\$color, 2); \n";
    TXT_CUSTOM="$TXT_CUSTOM \t\$bincolor[0] = hexdec(\"0x{\$hexcolor[0]}\"); \n\t \$bincolor[1] = hexdec(\"0x{\$hexcolor[1]}\"); \n";
    TXT_CUSTOM="$TXT_CUSTOM \t\$bincolor[2] = hexdec(\"0x{\$hexcolor[2]}\"); \n\t return \$bincolor; \n }";
    TXT_CUSTOM="$TXT_CUSTOM \n\nclass CCanvas {";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
# Customizando cores padrao para suportar cores dinamicas
    TAG_INICIO='##Zabbix-Extras-color-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "customizando cores padroes"; 
    else
        installMgs "N" "customizando cores padroes"; 
        TMP="blue";
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="global \$ZBXE_VAR;\n ";
    TXT_CUSTOM="$TXT_CUSTOM \$tmp = zbxeHtmlColorToRGB(\$ZBXE_VAR['map_background_color']); ";
    TXT_CUSTOM="$TXT_CUSTOM \n \$this->colors['red'] = imagecolorallocate(\$this->canvas, \$tmp[0],\$tmp[1], \$tmp[2]); \n";
    TXT_CUSTOM="$TXT_CUSTOM \$tmp = zbxeHtmlColorToRGB(\$ZBXE_VAR['map_border_color']); ";
    TXT_CUSTOM="$TXT_CUSTOM \n \$this->colors['darkred'] = imagecolorallocate(\$this->canvas, \$tmp[0],\$tmp[1], \$tmp[2]); \n";
    TXT_CUSTOM="$TXT_CUSTOM \$tmp = zbxeHtmlColorToRGB(\$ZBXE_VAR['map_title_color']); ";
    TXT_CUSTOM="$TXT_CUSTOM \n \$this->colors['green'] = imagecolorallocate(\$this->canvas, \$tmp[0],\$tmp[1], \$tmp[2]); \n";
    TXT_CUSTOM="$TXT_CUSTOM \$tmp = zbxeHtmlColorToRGB(\$ZBXE_VAR['map_date_color']); ";
    TXT_CUSTOM="$TXT_CUSTOM \n \$this->colors['darkgreen'] = imagecolorallocate(\$this->canvas, \$tmp[0],\$tmp[1], \$tmp[2]); \n";
    TXT_CUSTOM="$TXT_CUSTOM \n\t\t\$this->colors['blue'] = imagecolorallocate(\$this->canvas, 0, 0, 255);";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO

    # ------- customizacao para esconder o título dos mapas --------------------
    ARQUIVO="include/classes/sysmaps/CMapPainter.php";
    TAG_INICIO='##Zabbix-Extras-map-title-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "esconde titulo"; 
    else
        installMgs "N" "esconde titulo"; 
        TMP='\$this->canvas->drawTitle';
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="global \$ZBXE_VAR;\n if (intval(\$ZBXE_VAR['map_title_show']) > 0) { \n"
    TXT_CUSTOM="$TXT_CUSTOM \$this->canvas->drawTitle(\$this->mapData['name'], \$this->options['map']['titleColor']); \n }";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO

}
customLogo() {
    registra "Configurando suporte a logotipo personalizado...";
    ARQUIVO="include/page_header.php";
    TAG_INICIO='##Zabbix-Extras-map-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "logotipo"; #echo "--> Upgrade install (cores mapa)...";
    else
        installMgs "N" "logotipo"; #echo "--> Upgrade install (cores mapa)...";
        TMP="'zabbix_logo'";
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="\$newCDiv = new CDiv(SPACE, '');\n\$newCDiv->setAttribute('style', \"background: url('zbxe-logo.php') no-repeat; height: 31px; width: 250px; cursor: pointer;\");";
    TXT_CUSTOM="$TXT_CUSTOM\n\$logo = new CLink(\$newCDiv, 'http://www.zabbix.com/', 'image', null, 'nosid');\n";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
}

instalaGeo() {
    #REPOS="https://github.com/aristotelesaraujo/zabbix-geolocation/archive/master.zip";
# Repositório emergencial enquanto o Ari nao atualiza o repositorio oficial do zabbix-geo
    REPOS="https://github.com/SpawW/zabbix-geolocation/archive/master.zip";
    ARQ_TMP="/tmp/pluginGeo.zip";
    DIR_TMP="/tmp/zabbix-geolocation-master/";
    DIR_DEST="$CAMINHO_FRONTEND/extras/geo";

    downloadPackage "$ARQ_TMP" "$REPOS";
    unzipPackage "$ARQ_TMP" "$DIR_TMP" "$DIR_DEST";
    cp -Rp * "$DIR_DEST";

}


instalaPortletNS() {
    registra "Configurando portlet com link para itens nao suportados...";
    ARQUIVO="include/blocks.inc.php";
    TAG_INICIO='##Zabbix-Extras-NS-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "NS"; 
    else
        installMgs "N" "NS"; 
        TMP="items_count_not_supported";
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    #TXT_CUSTOM="new CSpan(\$status['items_count_not_supported'], 'unknown')";
    TXT_CUSTOM="new CLink(\$status['items_count_not_supported']\, 'zbxe-ns.php?groupid=0&hostid=0')";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
}

instalaMenus() {
    registra "Instalando menus customizados...";
    ARQUIVO="include/menu.inc.php";
    TAG_INICIO='##Zabbix-Extras-Menus-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "NS"; 
    else
        installMgs "N" "NS"; 
        TMP="\/\*\*";
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    #TXT_CUSTOM="new CSpan(\$status['items_count_not_supported'], 'unknown')";
    TXT_CUSTOM="global \$ZBXE_MENU;\n\$ZBX_MENU['zbxe'] = \$ZBXE_MENU;\n\/**";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
    # Verificação de instalação prévia do patch no javascript --------------
    if [ "`cat js/main.js | grep zbxe | wc -l`" -eq 0 ]; then
        LINHA=`cat js/main.js | sed -ne "/{'empty'\:/{=;q;}"`;
        registra "Instalando menu no javascript...";
        sed -i "104s/'admin': 0/'admin': 0,'zbxe':0/g" js/main.js 
    fi

}

instalaArvore() {
    #instalaPacote "php5-curl php-curl";
    REPOS="https://github.com/SpawW/zabbix-service-tree/archive/master.zip";
    ARQ_TMP="/tmp/pluginArvore.zip";
    DIR_TMP="/tmp/zabbix-service-tree-master/";
    DIR_DEST="$CAMINHO_FRONTEND/extras/service-tree"

    downloadPackage "$ARQ_TMP" "$REPOS";
    unzipPackage "$ARQ_TMP" "$DIR_TMP" "$DIR_DEST";
    cp -Rp * "$DIR_DEST";

    # Alterar arquivos
    TMP="\$ZABBIX_CONF = '$CAMINHO_FRONTEND/conf/zabbix.conf.php'";
    echo "$TMP;" >> "$CAMINHO_FRONTEND/extras/service-tree/__conf.php"
    TMP="$URL_FRONTEND";
    echo "\$ZABBIX_API = '$TMP';" >> "$CAMINHO_FRONTEND/extras/service-tree/__conf.php"

    instalaArvoreDeamon;
    instalaArvoreJS;
}

instalaArvoreDeamon() {
    REPOS="https://github.com/SpawW/zabbix-service-tree-daemon/archive/master.zip";
    ARQ_TMP="/tmp/pluginArvoreDaemon.zip";
    DIR_TMP="/tmp/zabbix-service-tree-daemon-master/";
    DIR_DEST="$CAMINHO_FRONTEND/extras/service-tree-daemon"

    downloadPackage "$ARQ_TMP" "$REPOS";
    unzipPackage "$ARQ_TMP" "$DIR_TMP" "$DIR_DEST";
    cp -Rp * "$DIR_DEST";

    # Alterar arquivos
}

instalaArvoreJS() {
    REPOS="https://github.com/SpawW/html5-tree-graph/archive/master.zip";
    ARQ_TMP="/tmp/pluginArvoreJS.zip";
    DIR_TMP="/tmp/html5-tree-graph-master/";
    DIR_DEST="$CAMINHO_FRONTEND/extras/service-tree-daemon"

    downloadPackage "$ARQ_TMP" "$REPOS";
    unzipPackage "$ARQ_TMP" "$DIR_TMP" "$DIR_DEST";
    cp -Rp * "$DIR_DEST";

    # Alterar arquivos
}
instalaZE() {
    REPOS="https://github.com/SpawW/zabbix-extras/archive/$BRANCH.zip";
    ARQ_TMP_BD="/tmp/pluginExtrasBD.htm";
    ARQ_TMP="/tmp/pluginExtras.zip";
    DIR_TMP="/tmp/zabbix-extras-$BRANCH/";

    downloadPackage "$ARQ_TMP" "$REPOS";
    unzipPackage "$ARQ_TMP" "$DIR_TMP" "$CAMINHO_FRONTEND";

    cp -Rp * "$CAMINHO_FRONTEND";
    registra "Iniciando banco de dados...";

    if [ -f "$ARQ_TMP_BD" ]; then
        rm "$ARQ_TMP_BD";
    fi
    wget "$URL_FRONTEND/zbxe-inicia-bd.php?p_modo_install=$UPDATEBD&p_versao_zbx=$VERSAO_ZBX" -O $ARQ_TMP_BD  --no-check-certificate;
    instalaLiteral;
}

commonUserChange() {
    ARQUIVO="$1";
# Adicao de Aba no profile do usuario para permitir configuracoes adicionais ---
    TAG_INICIO='##Zabbix-Extras-gui-custom';
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "personalizando profile"; 
    else
        installMgs "N" "personalizando profile"; 
        TMP='title';
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    TXT_CUSTOM="\n require_once ('include/views/zbxe.users.extra.edit.php');\n";
    TXT_CUSTOM="$TXT_CUSTOM \n \$page['title'] = _('User profile');";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO

    modifica "$ARQUIVO" "field-global" "Ajustando fields para modo global" '$fields = array(' 'global $fields;\n'
    modifica "$ARQUIVO" "check-fields" "Adicionando parametros personalizados" 'check_fields($fields);' 'zbxeFields();'
}

customProfile() {
    ARQUIVO="profile.php";
    commonUserChange "$ARQUIVO";
# Especifico do profile.php
    modifica "$ARQUIVO" "check-fields-rules" "Adicionando regra de negocio" '\/\/ secondary actions' 'zbxeControler();'
    ARQUIVO="users.php";
    commonUserChange "$ARQUIVO";
# Especifico do users.php
    TMPTAG="validate_sort_and_sortorder('alias', ZBX_SORT_UP);";
    modifica "$ARQUIVO" "check-fields-rules" "Adicionando regra de negocio" "$TMPTAG" 'zbxeControler();'
# Adicao das tabs 
    modifica "include/views/administration.users.edit.php" "users-interface" "Adicionando aba do extras no profile" '$userForm->addItem($userTab);' '$userTab = zbxeView($userTab);'
}

modifica() {
# $1 = Nome do arquivo
# $2 = TAG
# $3 = TAG Humana
# $4 = Texto identificador de inicio
# $5 = Texto customizado
# $6 = indica que a tag de inicio devera ficar antes do texto customizado
    ARQUIVO="$1";
    INI_COMENT="\/\/";

    TAG_INICIO="$INI_COMENT Zabbix-Extras-$2-custom";
    TAG_FINAL="$TAG_INICIO-FIM";
    INIINST=`cat $ARQUIVO | sed -ne "/$TAG_INICIO/{=;q;}"`;
    FIMINST=`cat $ARQUIVO | sed -ne "/$TAG_FINAL/{=;q;}"`;
    if [ ! -z $INIINST ]; then
        installMgs "U" "$3"; 
    else
        installMgs "N" "$3"; 
        TMP="$4";
        INIINST=`cat $ARQUIVO | sed -ne "/$TMP/{=;q;}"`;
        FIMINST=$INIINST;
    fi
    sed -i "$INIINST,$FIMINST d" $ARQUIVO;
    if [ -z $6 ]; then
        TXT_CUSTOM="$5 \n $4";
    else
        TXT_CUSTOM="$4 \n $5";
    fi
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
}

customItemKey() {
#    ARQUIVO="include/views/js/configuration.item.edit.js.php";
#    IDENT="if (type == 0 || type == 7 || type == 3 || type == 5 || type == 8 || type == 17) {";
    # Alterando o script JS
#    modifica "$ARQUIVO" "live-item-key" "Adicionando suporte para verificacao em tempo real de chave" "$IDENT" "jQuery('#keyButtonTest').prop('disabled', !(type == 0 || type == 7));"

    ARQUIVO="include/views/js/configuration.item.edit.js.php";
    IDENT="var type = parseInt(jQuery('#type').val());";
    # Alterando o script JS
    modifica "$ARQUIVO" "live-item-key" "Adicionando suporte para verificacao em tempo real de chave" "$IDENT" "jQuery('#keyButtonTest').prop('disabled', !(type == 0 || type == 7));\n" "S" 
    
    # Alterando o formulario de edicao de itens
    ARQUIVO="include/views/configuration.item.edit.php";
    IDENT="\t: null";
#    IDENT="         'formlist')\n       : null";
    NOVO=", (!\$this->data['limited'] ? new CButton('keyButtonTest', _('Test'),";
    NOVO=$NOVO"'iface=document.getElementById(\\\'interfaceid\\\'); ";
    NOVO=$NOVO" return PopUp(\"zbxe_item_test.php?hostid='.\$this->data['hostid'].'&itemid='.\$this->data['itemid']";
    NOVO=$NOVO".'&interface=\"+iface.options[iface.selectedIndex].text+\"'";
    NOVO=$NOVO".'&itemkey=\"+document.getElementById(";
    NOVO=$NOVO"\\\'key\\\').value)";
    NOVO=$NOVO"','formlist') : null )";
    # Alterando o script JS
    modifica "$ARQUIVO" "live-item-key" "Adicionando botao para suporte a verificacao em tempo real de chave" "$IDENT" "$NOVO" "S"
    
}

instalaSNMPB() {
    # Baixando arquivos do repositório -----------------------------------------

    REPOS="https://github.com/SpawW/snmpbuilder/archive/master.zip";
    ARQ_TMP="/tmp/pluginSNMPB.zip";
    DIR_TMP="/tmp/snmpbuilder-master/";
    DIR_DEST="$CAMINHO_FRONTEND/extras/snmp-builder"

    downloadPackage "$ARQ_TMP" "$REPOS";
    unzipPackage "$ARQ_TMP" "$DIR_TMP" "$DIR_DEST";
    cp -Rp * "$CAMINHO_FRONTEND";


    # Alterando o script JS 1
    ARQUIVO="$CAMINHO_FRONTEND/jsLoader.php";
    IDENT="'common.js' => '',";
    modifica "$ARQUIVO" "snmpb-jsscripts1" "Adicionando scripts para o snmp-builder-1" "$IDENT" "'DynTable.js' => 'snmp_builder/',\n'snmp_builder.js' => 'snmp_builder/',"

    # Alterando o script JS 2
    ARQUIVO="$CAMINHO_FRONTEND/jsLoader.php";
    IDENT="'jquery.js' => 'jquery\/',";
    modifica "$ARQUIVO" "snmpb-jsscripts2" "Adicionando scripts para o snmp-builder-2" "$IDENT" "'jquery.cookie.js' => 'jquery/',\n'jquery.jstree.js' => 'jquery/'," "S"
    # Adicionando arquivo CSS
    ARQUIVO="$CAMINHO_FRONTEND/include/page_header.php";
    IDENT="if (\$page\['file'\] == 'sysmap.php') {";
    modifica "$ARQUIVO" "snmpb-jsscripts2" "Adicionando scripts para o snmp-builder-2" "$IDENT" "\$pageHeader->addCssFile('js/jquery/themes/mib/style.css');"

    # Alterar arquivos de configuracao do snmp_builder
    TMP="define('MIBS_ALL_PATH', '$DIR_DEST/mibs');";
    echo "$TMP;" >> "$DIR_DEST/snmp-builder.conf.php"
    PATH_SNMP=`which snmptranslate | sed 's/\/snmptranslate//g'`;
    TMP="define('SNMPB_SNMP_PATH','$PATH_SNMP');";
    echo "$TMP;" >> "$DIR_DEST/snmp-builder.conf.php"

    # Configurando permissão da pasta de mibs
    chmod 777 -R "$DIR_DEST/mibs"

}

configuraPHP() {
  PATH_PHPINI='/etc/php.ini';
  if [ -f /tmp/upgZabbix/logInstall.log ]; then
    TMP=`cat /tmp/upgZabbix/logInstall.log | grep "Path do php.ini" | tail -n1 | awk -F[ '{print $2}' | awk -F] '{print $1}'`;
    if [ ! -z $TMP ]; then
        PATH_PHPINI=$TMP;
    fi
  fi
  dialog --inputbox "$M_BASE_PHP\n$M_CAMINHO_PHP" 0 0 "$PATH_PHPINI" 2> $TMP_DIR/resposta_dialog.txt;
    PATH_PHPINI=`cat $TMP_DIR/resposta_dialog.txt`;
  if [ ! -f "$PATH_PHPINI" ]; then
    registra $M_ERRO_CAMINHO_PHP"($PATH_PHPINI). "$M_ERRO_ABORT;
    exit 0;
  fi
  STATUSPHPINI=`cat $PATH_PHPINI  | grep ^"short_open_tag = Off" | wc -l`;
  if [ "$STATUSPHPINI" == "1" ]; then
    registra "Ativando short_open_tag... lembre-se de reiniciar o Apache!";
    sed -i 's/short_open_tag = Off/short_open_tag = On/g' "$PATH_PHPINI";
  else
    registra "Ja estava ativo short_open_tag!";
  fi
}

identificaZabbix() {
  cd $CAMINHO_FRONTEND;
  VERSAO_ZBX=`cat include/defines.inc.php | grep ZABBIX_VERSION | awk '{print $2}' | awk -F"'" '{print $2}'`;
  echo "--> Versao Zabbix: "$VERSAO_ZBX;
}
#modifica "nada.js.php" ;
#exit;

identificaDistro;
preReq;
idioma;
caminhoFrontend;
identificaZabbix;
configuraPHP;
downloadFiles;

# Criando pasta extras
if [ ! -e "$CAMINHO_FRONTEND/extras" ]; then
    mkdir -p "$CAMINHO_FRONTEND/extras";
fi


suporteBDCustom;
customMapas;
customLogo;
customItemKey;
instalaPortletNS;
instalaGeo;
instalaArvore;
instalaSNMPB;
instalaZE;
instalaMenus;
customProfile;

registra "Parametros usados para instalacao:";
registra "URL do Zabbix: [$URL_FRONTEND]";
registra "Path do frontend Zabbix: [$CAMINHO_FRONTEND]";
registra "Path do php.ini: [$PATH_PHPINI]";
registra "Se for necessario suporte favor enviar, por e-mail, os arquivos abaixo:";
registra "/tmp/pluginExtrasBD.htm";
registra "$TMP_DIR/logInstall.log";
exit;

