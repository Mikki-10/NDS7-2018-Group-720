%%
clear
clc

timeFrame = duration(8,0,0);

no_ms = tdfread('forks-no-delay.csv', ';');
ms_100 = tdfread('forks-100-ms.csv', ';');
s_1 = tdfread('forks-1sec-delay.csv', ';');

mined_no_delay = tdfread('mined-no-delay.csv', ';');
mined_100_ms = tdfread('mined-100-ms.csv', ';');
mined_1sec_delay = tdfread('mined-1sec-delay.csv', ';');


% Parse timestamps
no_ms = parseTimes(no_ms);
ms_100 = parseTimes(ms_100);
s_1 = parseTimes(s_1);
mined_no_delay = parseTimes(mined_no_delay);
mined_100_ms = parseTimes(mined_100_ms);
mined_1sec_delay = parseTimes(mined_1sec_delay);

% Get last X hours
forks_nodelay_sub = selectTimeframe(no_ms, timeFrame);
forks_100ms_delay_sub = selectTimeframe(ms_100, timeFrame);
forks_1s_delay_sub = selectTimeframe(s_1, timeFrame);
mined_no_delay_sub = selectTimeframe(mined_no_delay, timeFrame);
mined_100ms_delay_sub = selectTimeframe(mined_100_ms, timeFrame);
mined_1s_delay_sub = selectTimeframe(mined_1sec_delay, timeFrame);

% Calculate block times
block_time_no_delay = mined_no_delay_sub(2:end) - mined_no_delay_sub(1:end-1);
block_time_100_ms_delay = mined_100ms_delay_sub(2:end) - mined_100ms_delay_sub(1:end-1);
block_time_1s_delay = mined_1s_delay_sub(2:end) - mined_1s_delay_sub(1:end-1);

fork_count_nodelay = length(forks_nodelay_sub);
mined_count_nodelay = length(mined_no_delay_sub);
nodelay_relation = (fork_count_nodelay/mined_count_nodelay)*100;

fprintf('Timeframe: %s', evalc('disp(timeFrame)'))

fprintf('No delay:\n')
fprintf("Fork count: %d, Mined count: %d, Fork chance: %.2f%%, Mean block time: %.2f\n\n", fork_count_nodelay, mined_count_nodelay, nodelay_relation, mean(seconds(block_time_no_delay)))

fork_count_100_ms_delay = length(forks_100ms_delay_sub);
mined_count_100_ms_delay = length(mined_100ms_delay_sub);
delay_100_ms_relation = (fork_count_100_ms_delay/mined_count_100_ms_delay)*100;

fprintf('100 ms delay:\n')
fprintf("Fork count: %d, Mined count: %d, Fork chance: %.2f%%, Mean block time: %.2f\n\n", fork_count_100_ms_delay, mined_count_100_ms_delay, delay_100_ms_relation, mean(seconds(block_time_100_ms_delay)))

forks_count_1s_delay = length(forks_1s_delay_sub);
mined_count_1s_delay = length(mined_1s_delay_sub);
delay_1s_relation = (forks_count_1s_delay/mined_count_1s_delay)*100;

fprintf('1 s delay:\n')
fprintf("Fork count: %d, Mined count: %d, Fork chance: %.2f%%, Mean block time: %.2f\n\n", forks_count_1s_delay, mined_count_1s_delay, delay_1s_relation, mean(seconds(block_time_1s_delay)))

% Plot block time over time.
figure('Name', 'Block time over time')
subplot(3,1,1)
plot(mined_no_delay_sub(1:end-1), block_time_no_delay, 'DisplayName', 'Block time')
hold on
hold off
xlabel('Runtime')
ylabel('Block time')
legend
title('No delay mine time over time')

subplot(3,1,2)
plot(mined_100ms_delay_sub(1:end-1), block_time_100_ms_delay, 'DisplayName', 'Block time')
xlabel('Runtime')
ylabel('Block time')
legend
title('100ms delay mine time over time')

subplot(3,1,3)
plot(mined_1s_delay_sub(1:end-1), block_time_1s_delay, 'DisplayName', 'Block time')
xlabel('Runtime')
ylabel('Block time')
legend
title('1s delay mine time over time')


%%
clear
clc

no_ms = tdfread('mined-no-delay.csv', ';');
ms_100 = tdfread('mined-100-ms.csv', ';');
s_1 = tdfread('mined-1sec-delay.csv', ';');

% Parse timestamps
[no_ms(:).parsedTimes] = datetime(no_ms.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');
[no_ms(:).deltaT] = no_ms.parsedTimes - no_ms.parsedTimes(1);

% Parse timestamps
[ms_100(:).parsedTimes] = datetime(ms_100.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');
[ms_100(:).deltaT] = ms_100.parsedTimes - ms_100.parsedTimes(1);

% Parse timestamps
[s_1(:).parsedTimes] = datetime(s_1.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');
[s_1(:).deltaT] = s_1.parsedTimes - s_1.parsedTimes(1);

% Get indices of last X hours
%mined_delay = ms_100.deltaT(ms_100.deltaT > (ms_100.deltaT(end) - duration(4,0,0)) );
%mined_nodelay = no_ms.deltaT(no_ms.deltaT > (no_ms.deltaT(end) - duration(4,0,0)) );

mined_100_ms_delay = ms_100.deltaT;
mined_nodelay = no_ms.deltaT;
mined_1s_delay = s_1.deltaT;

mined_delta_100_ms_delay = mined_100_ms_delay(2:end) - mined_100_ms_delay(1:end-1);
mined_delta_nodelay = mined_nodelay(2:end) - mined_nodelay(1:end-1);
mined_delta_1_s_delay = mined_1s_delay(2:end) - mined_1s_delay(1:end-1);

figure('Name', 'Block times')
subplot(3,1,1)
histogram(mined_delta_nodelay)
xlabel('Block time')
ylabel('Frequency')
title('mined delta nodelay')

subplot(3,1,2)
histogram(mined_delta_100_ms_delay)
xlabel('Block time')
ylabel('Frequency')
title('mined delta 100ms delay')

subplot(3,1,3)
histogram(mined_delta_1_s_delay)
xlabel('Block time')
ylabel('Frequency')
title('mined delta 1s delay')

figure('Name', 'Block time over time')
subplot(3,1,1)
plot(mined_nodelay(1:end-1), mined_delta_nodelay)
xlabel('Runtime')
ylabel('Block time')
title('No delay mine time over time')

subplot(3,1,2)
plot(mined_100_ms_delay(1:end-1), mined_delta_100_ms_delay)
xlabel('Runtime')
ylabel('Block time')
title('100ms delay mine time over time')

subplot(3,1,3)
plot(mined_1s_delay(1:end-1), mined_delta_1_s_delay)
xlabel('Runtime')
ylabel('Block time')
title('1s delay mine time over time')

mean_100_ms_delay_mining = mean(seconds(mined_delta_100_ms_delay))
mean_nodelay_mining = mean(seconds(mined_delta_nodelay))
mean_1_s_delay_mining = mean(seconds(mined_delta_1_s_delay))

variance_100_ms_delay = var(seconds(mined_delta_100_ms_delay))
variance_nodelay = var(seconds(mined_delta_nodelay))
variance_1s_delay = var(seconds(mined_delta_1_s_delay))