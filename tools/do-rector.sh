#!/bin/bash

#-- goto root dir
cdir=$(dirname $(dirname $(readlink -f "$0")))
cd $cdir
printf "root dir= $cdir \n"
printf "target=   $1 \n"

#-- temp file
atmp=$(mktemp /tmp/do-rector-XXXXXX.log)
>$atmp
#ls -la $atmp
#exit 0;


##-- pre lint
#printf "\n\n--- linting: pre ---\n"
#find $1 -type f -name '*.php' -print0 | xargs -0 -n1 -P4 php -l -n 2>&1 >$atmp
#anum=$(cat $atmp | grep -v "No syntax errors detected" | wc -l)
#if [[ $anum -gt 0 ]]; then
#	cat $atmp | grep -v "No syntax errors detected"
#	exit 1
#fi


#-- pre reformat
printf "\n\n--- reformat: pre ---\n"
composer run fix:php
>$atmp

#-- loop
aloop=0
while :; do
	aloop=$((aloop + 1))
	printf "\n\n\n--- LOOP=$aloop ---\n\n"

	vendor/bin/rector process $1 2>&1 | tee -a $atmp

	#-- break if done
	anum=$(grep "Rector is done" $atmp | wc -l)
	if [[ $anum -gt 0 ]]; then break; fi

	#-- break if error
	bnum=$(grep "ERROR" $atmp | wc -l)
	if [[ $anum -gt 0 ]]; then break; fi

	#-- break if loop>5
	if [[ $aloop -gt 5 ]]; then break; fi

	#-- truncate log file
	>$atmp
done

#-- last reformat
printf "\n\n--- reformat: last ---\n"
composer run fix:php

rm -rf $atmp

#-- lint
printf "\n\n--- linting: last ---\n"
find $1 -type f -name '*.php' -exec php -l {} \; |grep -v "No syntax errors detected"
