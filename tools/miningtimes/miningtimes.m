clear

tdfread('sorted.csv', ';')

parsedTimes = datetime(MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');

difference = parsedTimes(2:end) - parsedTimes(1:end-1);

difference = seconds(difference(end-500:end));

% This line is super important for corrent time display if you use durations.
% Without it, Matlab will only print the duration down to the seond.
% difference.Format = 'hh:mm:ss.SSS';

mean_mining_time = mean(difference)
histogram(difference)
