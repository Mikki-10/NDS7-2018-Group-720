# REMOVE HEAD WHEN DONE!!!!!!!!

count_forks() {
    fork_count=0

    arr=("$@")

    for cb in "${arr[@]}"
    do
        drop_hashes=( $(ggrep "Chain split" $f | ggrep $cb | cut -d";" -f7 | uniq) )
        add_hashes=( $(ggrep "Chain split" $f | ggrep $cb | cut -d";" -f9 | uniq) )

        all_hashes=("${drop_hashes[@]}" "${add_hashes[@]}")

        all_hashes=($(echo "${all_hashes[@]}" | tr ' ' '\n' | sort -u | tr '\n' ' '))

        #echo "hash array: " "${all_hashes[@]}"

        heights=()
        for h in "${all_hashes[@]}"
        do
            heights+=( $(ggrep $h $f | ggrep "Propagated block" | cut -d";" -f4 | sort -r | head -n 1) )
        done

        #echo "height array: " "${heights[@]}"
        max=$(echo "${heights[@]}" | tr " " "\n" | sort | uniq -c | cut -d" " -f4 | sort -r | head -n 1)

        #echo "Max: " $max

        if [ $max -eq 1 ]
        then
            max=2
        fi

        fork_count=$(($fork_count+$max-1))

        #printf "\n"

    done

    echo $fork_count

}

files=$(ls subtracted-fri-30-2/*.csv | sort --version-sort)

for f in $files
do
	run_name=$(echo $f | cut -d"/" -f2)
    common_blocks=($(ggrep "Chain split" $f | cut -d";" -f5 | uniq))
    forks=$(count_forks "${common_blocks[@]}")
    mined=$(ggrep "mined potential" $f | cut -d";" -f5 | uniq | wc -l)
	pct_forks=$( awk "BEGIN {print ($forks/$mined)*100.0}" )
	blocktime=$(tail -n +2 $f | ggrep "mined" | cut -d";" -f1 | xargs -I {} gdate -d "{}" +"%s%N" | cut -b1-13 | ruby average.rb)
	echo "Run: " $run_name " Forks: " $forks " mined: " $mined " %_forks: " $pct_forks " blocktime: " $blocktime
	echo $run_name,$forks,$mined,$pct_forks,$blocktime >> plotdata-altforks-fri-30-2.csv
done
