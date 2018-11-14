function data = parseTimes( d )
    % Parse timestamps
    [d(:).parsedTimes] = datetime(d.MTime, 'InputFormat', 'MM-dd-HH:mm:ss.SSS');
    [d(:).deltaT] = d.parsedTimes - d.parsedTimes(1);
    data = d;
end