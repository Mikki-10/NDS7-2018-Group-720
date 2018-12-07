rm output/*
rm subtracted/*

for file in ./*.tar.gz
do
	name=$(basename -s ".tar.gz" $file)
	mkdir $name
	tar -xf $file -C $name

	./ethlogparser -s -i $name/logs/ | grep -v ";node" | sort > output/$name.csv
done

rm output/miner*.csv
rm output/node*.csv

files=$(find ./output -iname "*.csv" | sort --version-sort | cut -d"/" -f3)
fileCount=$(find ./output -iname "*.csv" | sort --version-sort | cut -d"/" -f3 | wc -l)
fileCount=$((fileCount-1))

for i in $(seq 1 $fileCount)
do
	subsfile=$(echo $files | cut -d" " -f$(($i)))
	outfile=$(echo $files | cut -d" " -f$(($i+1)))
	echo "Subtracting " $subsfile " from " $outfile
	comm -3 output/$subsfile output/$outfile | cut -c 2- > subtracted/$outfile

done

for file in subtracted/*.csv
do 
	echo -e "MTime;Miner;Message;BlockNr;Hash" | cat - $file > tmp && mv tmp $file
done