function tf = selectTimeframe( data, time)
    tf = data.deltaT(data.deltaT > (data.deltaT(end) - time) );
end