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

for i in {1..10}
do
	subsfile=$(echo $files | cut -d" " -f$(($i)))
	outfile=$(echo $files | cut -d" " -f$(($i+1)))

	comm -3 output/$subsfile output/$outfile | cut -c 2- > subtracted/$outfile

done

#for i in {0..9}
#do
#	comm -3 output/$i-*.csv output/$(($i+1))-*.csv | cut -c 2- > subtracted/$(($i+1))-$diffName
#done



#comm -3 output/startup-logs.csv output/delay100-loss0-logs.csv | cut -c 2- > subtracted/delay100-loss0-logs.csv
#
#comm -3 output/delay100-loss0-logs.csv output/delay200-loss0-logs.csv | cut -c 2- > subtracted/delay200-loss0-logs.csv
#
#comm -3 output/delay200-loss0-logs.csv output/delay300-loss0-logs.csv | cut -c 2- > subtracted/delay300-loss0-logs.csv
#
#comm -3 output/delay300-loss0-logs.csv output/delay400-loss0-logs.csv | cut -c 2- > subtracted/delay400-loss0-logs.csv
#
#comm -3 output/delay400-loss0-logs.csv output/delay500-loss0-logs.csv | cut -c 2- > subtracted/delay500-loss0-logs.csv
#
#comm -3 output/delay500-loss0-logs.csv output/delay600-loss0-logs.csv | cut -c 2- > subtracted/delay600-loss0-logs.csv
#
#comm -3 output/delay600-loss0-logs.csv output/delay700-loss0-logs.csv | cut -c 2- > subtracted/delay700-loss0-logs.csv
#
#comm -3 output/delay700-loss0-logs.csv output/delay800-loss0-logs.csv | cut -c 2- > subtracted/delay800-loss0-logs.csv
#
#comm -3 output/delay800-loss0-logs.csv output/delay900-loss0-logs.csv | cut -c 2- > subtracted/delay900-loss0-logs.csv
#
#comm -3 output/delay900-loss0-logs.csv output/delay1000-loss0-logs.csv | cut -c 2- > subtracted/delay1000-loss0-logs.csv

for file in subtracted/*.csv
do 
	echo -e "MTime;Miner;Message;BlockNr;Hash" | cat - $file > tmp && mv tmp $file
done