files=$(ls subtracted-linear-thu-6-dec-0-400ms-3/*.csv | sort --version-sort)

for f in $files
do
	run_name=$(echo $f | cut -d"/" -f2)
	chainsplits=$(ggrep "Chain split" $f | cut -d";" -f5 | uniq )

	echo "Fork lifetime in $run_name:"

	for cs in $chainsplits
	do
		resolution=$(ggrep $cs $f | ggrep "Chain split" | tail -n -1)
		endTimestamp=$(echo $resolution | cut -d";" -f1)
		dropHash=$(echo $resolution | cut -d";" -f7)
		dropLength=$(echo $resolution | cut -d";" -f6)
		#echo $resolution
		#echo $endTimestamp
		#echo $dropHash

		mined=$(ggrep $dropHash $f | ggrep "mined")
		minedTimestamp=$(echo $mined | cut -d";" -f1)

		#echo $mined
		#echo $minedTimestamp


		# Convert to epoch milliseconds
		endTime=$(gdate -d "${endTimestamp}" +"%s%N" | cut -b1-13)
		minedTime=$(gdate -d "${minedTimestamp}" +"%s%N" | cut -b1-13)

		lifetime=$( awk "BEGIN {print ($endTime-$minedTime)/1000}" )

		printf "\tChain split by %s: %s length: %s\n" $dropHash $lifetime $dropLength

		echo "${run_name},${lifetime},${dropLength}" >> forklifetime/durp.csv
	done

done