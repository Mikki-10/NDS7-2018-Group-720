# REMOVE HEAD WHEN DONE!!!!!!!!

file="subtracted-fri-30-2/11-delay400-loss0-logs.csv"

common_blocks=$(ggrep "Chain split" $file | cut -d";" -f5 | uniq)


for cb in $common_blocks
do
    drop_hashes=( $(ggrep "Chain split" $file | ggrep $cb | cut -d";" -f7 | uniq) )
    add_hashes=( $(ggrep "Chain split" $file | ggrep $cb | cut -d";" -f9 | uniq) )

    all_hashes=("${drop_hashes[@]}" "${add_hashes[@]}")

    all_hashes=($(echo "${all_hashes[@]}" | tr ' ' '\n' | sort -u | tr '\n' ' '))

    echo "hash array: " "${all_hashes[@]}"

    heights=()
    for h in "${all_hashes[@]}"
    do
        heights+=( $(ggrep $h $file | ggrep "Propagated block" | cut -d";" -f4 | sort -r | head -n 1) )
    done

    echo "height array: " "${heights[@]}"
    max=$(echo "${heights[@]}" | tr " " "\n" | sort | uniq -c | cut -d" " -f4 | sort -r | head -n 1)

    echo "Max: " $max

    fork_count=$(($fork_count+$max-1))


    printf "\n"

done

echo $fork_count