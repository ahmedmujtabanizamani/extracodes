# tmp vars
cookies="/c/tmp/gmcookie.txt"
logFile="/c/users/mujta/logs/signals.txt"
scriptPath="/c/users/mujta/repositories/scripts/Routers"
passwordStr="Password String"

cd "${scriptPath}"
# Signin
login=$(curl 'https://192.168.1.52/api/auth' --data-raw "username=ubnt&password=${passwordStr}"   --insecure -c "${cookies}" -s)
# Status
status=$(curl 'https://192.168.1.52/status.cgi' --insecure -b "${cookies}" -s)

# Signal Local
signalL=$(echo $status | grep -oE "signal\":?\+?\-[0-9]*" | grep "" -n | grep -E "^1:" | grep -oE "[0-9]*$")
printf -v signalL_str '%0.2d' $signalL
# Download Capacity Local
dl_cap=$(echo $status | grep -oE "dl_capacity\":[0-9]*" | grep "" -n | grep -E "^1:" | grep -oE "[0-9]*$")
printf -v dl_cap_str '%0.3d M' $(($dl_cap/1024))

# Actual Download Local
a_dl_l=$(echo $status | grep -oE "throughput\":{[^\}]*" | grep -oE "[0-9]*$")
printf -v a_dl_l_str '%0.3d M' $(($a_dl_l/1024))

# Actual Upload Local
a_ul_l=$(echo $status | grep -oE "throughput\":{[^,}]*" | grep -oE "[0-9]*$")
printf -v a_ul_l_str '%0.3d M' $(($a_ul_l/1024))

# Lan speed
lls=$(echo $status | grep -oE "interfaces\":[^]]*" | grep -oE "speed\":[^\,]*" | grep -oE "[0-9]+$")
printf -v lls_str '%0.4d M' $lls

#uptime
ut=$(echo $status | grep -oE "uptime\":[^\",]*" | grep -n ":" | grep -oE 1:[^$]* | grep -oE "[0-9]+$")

# uptime days
utd=$((ut / 60 / 60 / 24))
printf -v utd_str '%0.3d d' $utd
# uptime hours
uth=$(((ut / 60 / 60) - (utd) * 24))
printf -v uth_str '%0.2d h' $uth

if [ ${#dl_cap} -gt 0 ]; then
status_str=$(echo $(date '+%d-%m-%y %H:%M') " | signal:" $signalL_str " - cap:" $dl_cap_str " - dl:" $a_dl_l_str " - ul:" $a_ul_l_str " - LLS:" $lls_str " - UT: " $utd_str ":" $uth_str)

echo $status_str >> "${logFile}"
fi
# remove cookie file
rm "${cookies}"