clear

no_ms = tdfread('forks-no-delay.csv', ';');
ms_100 = tdfread('forks-100-ms.csv', ';');

% Parse timestamps
[no_ms(:).parsedTimes] = datetime(no_ms.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');

[no_ms(:).deltaT] = no_ms.parsedTimes - no_ms.parsedTimes(1);

% Parse timestamps
[ms_100(:).parsedTimes] = datetime(ms_100.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');

[ms_100(:).deltaT] = ms_100.parsedTimes - ms_100.parsedTimes(1);

% Get indices of last X hours
forks_delay = ms_100.deltaT(ms_100.deltaT > (ms_100.deltaT(end) - duration(4,0,0)) );
forks_nodelay = no_ms.deltaT(no_ms.deltaT > (no_ms.deltaT(end) - duration(4,0,0)) );

%%
clear
clc

no_ms = tdfread('mined-no-delay.csv', ';');
ms_100 = tdfread('mined-100-ms.csv', ';');

% Parse timestamps
[no_ms(:).parsedTimes] = datetime(no_ms.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');

[no_ms(:).deltaT] = no_ms.parsedTimes - no_ms.parsedTimes(1);

% Parse timestamps
[ms_100(:).parsedTimes] = datetime(ms_100.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');

[ms_100(:).deltaT] = ms_100.parsedTimes - ms_100.parsedTimes(1);

% Get indices of last X hours
%mined_delay = ms_100.deltaT(ms_100.deltaT > (ms_100.deltaT(end) - duration(4,0,0)) );
%mined_nodelay = no_ms.deltaT(no_ms.deltaT > (no_ms.deltaT(end) - duration(4,0,0)) );

mined_delay = ms_100.deltaT;
mined_nodelay = no_ms.deltaT;

mined_delta_delay = mined_delay(2:end) - mined_delay(1:end-1);
mined_delta_nodelay = mined_nodelay(2:end) - mined_nodelay(1:end-1);

figure('Name','mined_delta_delay')
histogram(mined_delta_delay)
xlabel('Mine time')
ylabel('Frequency')

figure('Name','mined_delta_nodelay')
histogram(mined_delta_nodelay)
xlabel('Mine time')
ylabel('Frequency')


figure('Name','100ms delay mine time over time')
plot(mined_delay(1:end-1), mined_delta_delay)
xlabel('Runtime')
ylabel('Mine time')

figure('Name','No delay mine time over time')
plot(mined_nodelay(1:end-1), mined_delta_nodelay)
xlabel('Runtime')
ylabel('Mine time')

mean_delay_mining = mean(seconds(mined_delta_delay))
mean_nodelay_mining = mean(seconds(mined_delta_nodelay))

variance_delay = var(seconds(mined_delta_delay))
variance_nodelay = var(seconds(mined_delta_nodelay))