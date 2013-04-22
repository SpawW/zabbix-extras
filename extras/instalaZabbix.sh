
mkdir /install
cd /install/


TIPO_SO=`uname -a | grep 386 | wc -l`;
if [ "$TIPO_SO" == "0" ] then
  TIPO_SO='i386';
else
  TIPO_SO='x86_64';
fi
if [ -e /etc/redhat-release ] then
  if [ `cat /etc/redhat-release | grep -i centos | wc -l` == 1 ] then
    echo "--CentOS";
  fi
else
  echo "outro so";
fi
