function data = parseTimes2( d )
    % Parse timestamps: example: 2018-11-07 09:39:30.193
    [d(:).parsedTimes] = datetime(d.MTime, 'InputFormat', 'yyyy-MM-dd HH:mm:ss.SSS');
    [d(:).deltaT] = d.parsedTimes - d.parsedTimes(1);
    data = d;
end