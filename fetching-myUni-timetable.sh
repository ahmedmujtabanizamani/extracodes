hdir="/tmp/timetablehistory"
mkdir -p $hdir
touch $hdir/history.txt
teacher=$(grep -i "teacher: ${2}" $hdir/*) 
class=$(grep -i "class: ${2}" $hdir/*)
file="not found"
S="not set"
echo "checking is there any previews record!!.."
if [[ $teacher == "" ]] && [[ $class == "" ]]; then 
	echo "not found any record.."
	echo "fetching full timetable..."
	mkdir /tmp/timetable
	cd /tmp/timetable

	if [ ${1} == "c" ]; then
		echo "fetching from class timetable..."
		S="class: ${2}"
		curl --silent -O http://103.12.58.108/timetable/form_[001-053].html
	elif [ $1 == "t" ]; then
		echo "fetching from teachers timetable"
		S="teacher: ${2}"
		curl --silent -O http://103.12.58.108/timetable/teacher_[000-036].html
	else
		exit 1
	fi
	file=$(echo $(grep -i "${S}" /tmp/timetable/*) | awk -F ":" '{print $1}')
	mv /tmp/timetable/* /tmp/timetablehistory/
	rm -rf /tmp/timetable
	echo "successfully fetched..."
	/home/mujtaba/timetable/tt.sh
	exit 1
else
	echo "previews record found!.."
fi
if [[ $teacher == "" ]]; then
	S="class: ${2}"
elif [[ $class == "" ]]; then
	S="teacher: ${2}"
fi
sleep 1
file=$(echo $(grep -i "${S}" /tmp/timetablehistory/*) | awk -F ":" '{print $1}')
newf=$(echo $file | awk -F "/" '{print $4}')
url="http://103.12.58.108/timetable/${newf}"
echo "updating timetable.."
echo "fetching timetable from '${url}'..."
curl -# -o $file $url
w3m $hdir/$newf


#curl --silent -O http://isra.edu.pk/timetable/room_[000-035].html

