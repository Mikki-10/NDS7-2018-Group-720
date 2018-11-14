%% convert CSV to mat
clear
inputFiles = dir('./input/*.csv');

for file = inputFiles'
   fprintf("Converting: %s\n", file.name)
   contents = tdfread( strcat(file.folder,'/',file.name), ';');
   contents = parseTimes2(contents);
   save(strcat('./tmp/',file.name,'.mat'), 'contents');
end
clear
%% Load files
clear
close all
clc
inputFiles = dir('./tmp/*.mat');

timeWindow = duration(8,0,0);

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

    fprintf('Timeframe: %s', evalc('disp(timeWindow)'))
    fprintf('%s:\n', inputFiles(dataCounter).name)

    fprintf("Fork count: %d, Mined count: %d, Fork chance: %.2f%%, Mean block time: %.2f\n\n", fork_count, mined_count, fork_chance, mean(seconds(block_time)))

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
