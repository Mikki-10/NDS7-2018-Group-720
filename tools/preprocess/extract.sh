for file in ./*.tar.gz
do
	name=$(basename -s ".tar.gz" $file)
	mkdir $name
	tar -xf $file -C $name

	./ethlogparser -s -i $name/logs/ | sort > output/$name.csv

done

comm -3 output/startup-logs.csv output/delay100-loss0-logs.csv | cut -c 2- > subtracted/delay100-loss0-logs.csv

comm -3 output/delay100-loss0-logs.csv output/delay200-loss0-logs.csv | cut -c 2- > subtracted/delay200-loss0-logs.csv

comm -3 output/delay200-loss0-logs.csv output/delay300-loss0-logs.csv | cut -c 2- > subtracted/delay300-loss0-logs.csv

comm -3 output/delay300-loss0-logs.csv output/delay400-loss0-logs.csv | cut -c 2- > subtracted/delay400-loss0-logs.csv

comm -3 output/delay400-loss0-logs.csv output/delay500-loss0-logs.csv | cut -c 2- > subtracted/delay500-loss0-logs.csv

comm -3 output/delay500-loss0-logs.csv output/delay600-loss0-logs.csv | cut -c 2- > subtracted/delay600-loss0-logs.csv

comm -3 output/delay600-loss0-logs.csv output/delay700-loss0-logs.csv | cut -c 2- > subtracted/delay700-loss0-logs.csv

comm -3 output/delay700-loss0-logs.csv output/delay800-loss0-logs.csv | cut -c 2- > subtracted/delay800-loss0-logs.csv

comm -3 output/delay800-loss0-logs.csv output/delay900-loss0-logs.csv | cut -c 2- > subtracted/delay900-loss0-logs.csv

comm -3 output/delay900-loss0-logs.csv output/delay1000-loss0-logs.csv | cut -c 2- > subtracted/delay1000-loss0-logs.csv

for file in subtracted/*.csv
do 
	echo -e "MTime;Miner;Message;BlockNr;Hash" | cat - $file > tmp && mv tmp $file
done