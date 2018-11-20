%% convert CSV to mat
% MTime;Miner;Message;BlockNr;Hash
clear
inputFiles = dir('./input/*.csv');

for file = inputFiles'
   fprintf("Converting: %s\n", file.name)
   contents = tdfread( strcat(file.folder,'/',file.name), ';');
   contents = parseTimes2(contents);
   save(strcat('./tmp/',file.name,'.mat'), 'contents');
end
%% Load files
clear
close all
clc
inputFiles = dir('./tmp/*.mat');

[~, idx] = natsort({inputFiles.name});
%inputFiles = orderfields(inputFiles, idx);

for i = 1:length(inputFiles)
   newinput(i) = inputFiles( idx(i) ); 
end

inputFiles = newinput;

timeWindow = duration(8,0,0);

results = repmat(struct('name', '', 'fork_count', 0, 'mined_count', 0, 'fork_chance', 0, 'block_time', seconds(0)), length(inputFiles), 1);

%results = zeros(length(inputFiles),1);

for i = 1:length(inputFiles)
    data{i} = load( strcat(inputFiles(i).folder,'/',inputFiles(i).name) );
end

fig_blocktime = figure('Name', 'Block time over time');

for dataCounter = 1:length(inputFiles)

    % get mined block times
    mine_timestamps = filterLog(data{dataCounter}.contents.Message, 'mined potential', data{dataCounter}.contents.deltaT);

    % get forks
    fork_timestamps = filterLog(data{dataCounter}.contents.Message, 'forked', data{dataCounter}.contents.deltaT);

    % select time frame
    endtime = data{dataCounter}.contents.deltaT(end);
    mine_timeframe = mine_timestamps(mine_timestamps > (endtime - timeWindow) );
    fork_timeframe = fork_timestamps(fork_timestamps > (endtime - timeWindow) );

    % Caluclate time between each mined block
    block_time = mine_timeframe(2:end) - mine_timeframe(1:end-1);
    fork_count = length(fork_timeframe);
    mined_count = length(mine_timeframe);

    fork_chance = (fork_count/mined_count)*100;

    startTimestamp = data{dataCounter}.contents.parsedTimes(1);
    endTimestamp = data{dataCounter}.contents.parsedTimes(end);
    
    fprintf('Time window: %s', evalc('disp(timeWindow)'))
    fprintf('%s, Start: %s, End: %s:\n', inputFiles(dataCounter).name, string(startTimestamp), string(endTimestamp))

    fprintf("Fork count: %d, Mined count: %d, Fork chance: %.2f%%, Mean block time: %.2f\n\n", fork_count, mined_count, fork_chance, mean(seconds(block_time)))
    results(dataCounter).name = inputFiles(dataCounter).name;
    results(dataCounter).fork_count = fork_count;
    results(dataCounter).mined_count = mined_count;
    results(dataCounter).fork_chance = fork_chance;
    results(dataCounter).block_time = mean(seconds(block_time));
    
    % Plot block time over time.
    figure(fig_blocktime)
    subplot(length(inputFiles),1,dataCounter)
    plot(mine_timeframe(1:end-1), block_time, 'DisplayName', 'Block time')
    hold on
    % Plot fork indicators
    stem([fork_timeframe fork_timeframe], repmat(ylim, 1, length(fork_timeframe)), 'MarkerFaceColor', 'none', 'MarkerEdgeColor', 'none', 'LineWidth', 1);
    hold off
    
    xlabel('Runtime')
    ylabel('Block time')
    ylim([duration(0, 0, 0) duration(0, 2, 0)])
    legend
    title( ['Block time - ' inputFiles(dataCounter).name] )
    
end
figure(3)
c = {results.name};
bar([results.fork_chance]);
xticklabels(c);
xtickangle(45);
xlabel('Test run number');
ylabel('% forks');


%% Plot single test split up into windows.
clear fig_blocktime_single_run
clc
fig_blocktime_single_run = figure('Name', 'Block time over time single run');

dataCounter = 2;

% get mined block times
mine_timestamps = filterLog(data{dataCounter}.contents.Message, 'mined potential', data{dataCounter}.contents.deltaT);

% get forks
fork_timestamps = filterLog(data{dataCounter}.contents.Message, 'forked', data{dataCounter}.contents.deltaT);

% select time frame
endtime = data{dataCounter}.contents.deltaT(end);
for i = 1:8
    mine_timeframes{i} = mine_timestamps(mine_timestamps > (endtime - duration(i, 0, 0)));
    mine_timeframes{i} = mine_timeframes{i}(mine_timeframes{i} < (endtime - duration(i-1, 0, 0)));
    
    fork_timeframes{i} = fork_timestamps(fork_timestamps > (endtime - duration(i, 0, 0)));
    fork_timeframes{i} = fork_timeframes{i}(fork_timeframes{i} < (endtime - duration(i-1, 0, 0)));
end

for i = 1:8

    mine_timeframe = mine_timeframes{i}; %mine_timestamps(mine_timestamps > (endtime - timeWindow) );
    fork_timeframe = fork_timeframes{i}; %fork_timestamps(fork_timestamps > (endtime - timeWindow) );

    % Caluclate time between each mined block
    block_time = mine_timeframe(2:end) - mine_timeframe(1:end-1);
    fork_count = length(fork_timeframe);
    mined_count = length(mine_timeframe);

    fork_chance = (fork_count/mined_count)*100;

    %fprintf('Timeframe: %s', evalc('disp(timeWindow)'))
    fprintf('%s - part: %d:\n', inputFiles(dataCounter).name, i)

    fprintf("Fork count: %d, Mined count: %d, Fork chance: %.2f%%, Mean block time: %.2f\n\n", fork_count, mined_count, fork_chance, mean(seconds(block_time)))

    % Plot block time over time.
    figure(fig_blocktime_single_run)
    subplot(length(mine_timeframes),1,i)
    plot(mine_timeframe(1:end-1), block_time, 'DisplayName', 'Block time')
    hold on
    % Plot fork indicators
    stem([fork_timeframe fork_timeframe], repmat(ylim, 1, length(fork_timeframe)), 'MarkerFaceColor', 'none', 'MarkerEdgeColor', 'none', 'LineWidth', 1);
    hold off

    xlabel('Runtime')
    ylabel('Block time')
    ylim([duration(0, 0, 0) duration(0, 2, 0)])
    legend
    title( ['Block time - ' inputFiles(dataCounter).name] )

end