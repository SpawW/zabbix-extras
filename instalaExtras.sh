#!/bin/bash
# Autor: Adail Horst
# Email: the.spaww@gmail.com

INSTALAR="N";
AUTOR="the.spaww@gmail.com";
TMP_DIR="/tmp/upgZabbix";
VERSAO_INST="2.0.1-rc3";
UPDATEBD="S";
#DATA_BACKUP=`date +%Y%m%d`;

registra() {
    echo $(date)" - $1" >> $TMP_DIR/logInstall.log;
    echo "--> $1";
}
installMgs() {
    if [ "$1" = "U" ]; then
        tipo="Upgrade";
        recriaTabelas;
    else
        tipo="Clean";
    fi
    registra " $tipo install ($2)...";
}

recriaTabelas() {
    dialog \
        --title 'Zabbix Extras BD Update ['$VERSAO_INST']'        \
        --radiolist $M_UPGRADE_BD  \
        0 0 0                                    \
        S   $M_UPGRADE_BD_SIM  on    \
        N   $M_UPGRADE_BD_NAO  off   \
        2> $TMP_DIR/resposta_dialog.txt
    UPDATEBD=`cat $TMP_DIR/resposta_dialog.txt `;
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
            ;;
	*) 
      M_BASE="This installer will add an extra menu to the end of the menu bar of your environment. For installation are needed to inform some parameters.";
      M_CAMINHO="Please enter the path to the zabbix frontend ";
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
    case $LINUX_DISTRO in
	"ubuntu" | "debian" | "red hat" | "red" | "centos" | "opensuse" | "opensuse" | "amazon" )
            CAMINHO_RCLOCAL="/etc/rc.local";
            registra "Versao do Linux - OK ($LINUX_DISTRO - $LINUX_VER)"
            ;;
	*) 
            registra "Distribucao nao prevista ($LINUX_DISTRO)... favor contactar $AUTOR"; exit 1; 
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
    dialog --inputbox "$M_BASE\n$M_URL" 0 0 "http://localhost/zabbix" 2> $TMP_DIR/resposta_dialog.txt;
    URL_FRONTEND=`cat $TMP_DIR/resposta_dialog.txt`;
}

expandePatch() {
    # Download de arquivos para instalacao ------------------------------------------------------
    if [ "$DOWNLOAD" = "S" ]; then
       if [ -f "$CAMINHO_EXTRAS" ]; then
         rm -f "$CAMINHO_EXTRAS";
       fi
       wget "http://spinola.net.br/zabbix-extras/lastVersion.tgz" -o $CAMINHO_EXTRAS --no-check-certificate;
    fi
    # Instalando arquivos da customizacao -------------------------------------------------------
    clear;
    cd $CAMINHO_FRONTEND;
    tar -xzvf "$CAMINHO_EXTRAS";
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
#AQUI !!!!!!!!!!!!!!!!
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
    REPOS="https://github.com/SpawW/zabbix-geolocation/archive/master.zip";
    ARQ_TMP="/tmp/pluginGeo.zip";
    if [ -f $ARQ_TMP ]; then
        rm $ARQ_TMP;
    fi
    # Baixa repositorio
    wget $REPOS -O $ARQ_TMP --no-check-certificate;
    cd /tmp;
    # Descompacta em TMP
    if [ -e /tmp/zabbix-geolocation-master/ ]; then
        unalias rm;
        rm -rf /tmp/zabbix-geolocation-master/;
    fi
    unzip pluginGeo.zip
    cd /tmp/zabbix-geolocation-master/
    # Move para /
    #mv misc/geolocation.php $CAMINHO_FRONTEND/
    # Move para /extras    
    if [ ! -e "$CAMINHO_FRONTEND/extras/geo" ]; then
        mkdir -p "$CAMINHO_FRONTEND/extras/geo";
    fi
    cp -Rp * "$CAMINHO_FRONTEND/extras/geo";
    # Alterar arquivos
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
        sed -i "106s/'admin': 0/'admin': 0,'zbxe':0/g" js/main.js 
    fi

}

instalaArvore() {
    #instalaPacote "php5-curl php-curl";
    REPOS="https://github.com/SpawW/zabbix-service-tree/archive/master.zip";
    ARQ_TMP="/tmp/pluginArvore.zip";
    DIR_TMP="/tmp/zabbix-service-tree-master/";
    if [ -f $ARQ_TMP ]; then
        rm $ARQ_TMP;
    fi
    # Baixa repositorio
    wget $REPOS -O $ARQ_TMP --no-check-certificate;
    cd /tmp;
    # Descompacta em TMP
    if [ -e $DIR_TMP ]; then
        unalias rm;
        rm -rf $DIR_TMP;
    fi
    unzip $ARQ_TMP;
    cd $DIR_TMP
    # Move para /extras    
    if [ ! -e "$CAMINHO_FRONTEND/extras/service-tree" ]; then
        mkdir -p "$CAMINHO_FRONTEND/extras/service-tree";
    fi
    cp -Rp * "$CAMINHO_FRONTEND/extras/service-tree";
    # Alterar arquivos
    #cat "$CAMINHO_FRONTEND/extras/service-tree/__conf.php" | grep -v "^\$ZABBIX" > "$CAMINHO_FRONTEND/extras/service-tree/__conf.php"
#set -x
    TMP="\$ZABBIX_CONF = '$CAMINHO_FRONTEND/conf/zabbix.conf.php'";
    echo "$TMP;" >> "$CAMINHO_FRONTEND/extras/service-tree/__conf.php"
    TMP="$URL_FRONTEND";
    echo "\$ZABBIX_API = '$TMP';" >> "$CAMINHO_FRONTEND/extras/service-tree/__conf.php"
#set +x;
    instalaArvoreDeamon;
    instalaArvoreJS;
}

instalaArvoreDeamon() {
    REPOS="https://github.com/SpawW/zabbix-service-tree-daemon/archive/master.zip";
    ARQ_TMP="/tmp/pluginArvoreDaemon.zip";
    DIR_TMP="/tmp/zabbix-service-tree-daemon-master/";
    if [ -f $ARQ_TMP ]; then
        rm $ARQ_TMP;
    fi
    # Baixa repositorio
    wget $REPOS -O $ARQ_TMP --no-check-certificate;
    cd /tmp;
    # Descompacta em TMP
    if [ -e $DIR_TMP ]; then
        unalias rm;
        rm -rf $DIR_TMP;
    fi
    unzip $ARQ_TMP;
    cd $DIR_TMP
    if [ ! -e "$CAMINHO_FRONTEND/extras/service-tree-daemon" ]; then
        mkdir -p "$CAMINHO_FRONTEND/extras/service-tree-daemon";
    fi
    cp -Rp * "$CAMINHO_FRONTEND/extras/service-tree-daemon";
    # Alterar arquivos
}

instalaArvoreJS() {
    REPOS="https://github.com/SpawW/html5-tree-graph/archive/master.zip";
    ARQ_TMP="/tmp/pluginArvoreJS.zip";
    DIR_TMP="/tmp/html5-tree-graph-master/";
    if [ -f $ARQ_TMP ]; then
        rm $ARQ_TMP;
    fi
    # Baixa repositorio
    wget $REPOS -O $ARQ_TMP --no-check-certificate;
    cd /tmp;
    # Descompacta em TMP
    if [ -e $DIR_TMP ]; then
        unalias rm;
        rm -rf $DIR_TMP;
    fi
    unzip $ARQ_TMP;
    cd $DIR_TMP
    if [ ! -e "$CAMINHO_FRONTEND/extras/service-tree-daemon" ]; then
        mkdir -p "$CAMINHO_FRONTEND/extras/service-tree-daemon";
    fi
    cp -Rp * "$CAMINHO_FRONTEND/extras/service-tree-daemon";
    # Alterar arquivos
}
instalaZE() {
    REPOS="https://github.com/SpawW/zabbix-extras/archive/master.zip";
    ARQ_TMP="/tmp/pluginExtras.zip";
    ARQ_TMP_BD="/tmp/pluginExtrasBD.htm";
    DIR_TMP="/tmp/zabbix-extras-master/";
    if [ -f $ARQ_TMP ]; then
        rm $ARQ_TMP;
    fi
    if [ -f $ARQ_TMP2 ]; then
        rm $ARQ_TMP2;
    fi
    # Baixa repositorio
    wget $REPOS -O $ARQ_TMP --no-check-certificate;
    cd /tmp;
    # Descompacta em TMP
    unalias rm;
    if [ -e $DIR_TMP ]; then
        rm -rf $DIR_TMP;
    fi
    unzip $ARQ_TMP;
    cd $DIR_TMP
    cp -Rp * "$CAMINHO_FRONTEND";
    registra "Iniciando banco de dados...";
#set -x;
    if [ -f "$ARQ_TMP_BD" ]; then
        rm "$ARQ_TMP_BD";
    fi
    wget "$URL_FRONTEND/zbxe-inicia-bd.php?p_modo_install=$UPDATEBD" -O $ARQ_TMP_BD  --no-check-certificate;
#    rm "./zbxe-inicia-bd.php";
#set +x;
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
    ARQUIVO="$1";
    TAG_INICIO="##Zabbix-Extras-$2-custom";
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
    TXT_CUSTOM="$5 \n $4";
    sed -i "$INIINST i$TAG_INICIO\n$TXT_CUSTOM\n$TAG_FINAL" $ARQUIVO
}

identificaDistro;
preReq;
idioma;
caminhoFrontend;

suporteBDCustom;
customMapas;
customLogo;
instalaPortletNS;
instalaGeo;
instalaArvore;
instalaZE;
instalaMenus;
customProfile;

registra "Parametros usados para instalacao:";
registra "URL do Zabbix: [$URL_FRONTEND]";
registra "Path do frontend Zabbix: [$CAMINHO_FRONTEND]";
registra "Se for necessario suporte favor enviar, por e-mail, os arquivos abaixo:";
registra "/tmp/pluginExtrasBD.htm";
registra "$TMP_DIR/logInstall.log";
exit;

