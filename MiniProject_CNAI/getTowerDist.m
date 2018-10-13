% Calculates the eucledian distance between towers and walker.
% If third argument is 1, gaussian noise is added to the distances.
function d = getTowerDist(towerCoords, walkerCoords, addNoise)
    d = [];
    for t = towerCoords'
        towerDistVec = t - walkerCoords';
        d = [d; vecnorm(towerDistVec)];
    end
    
    if(addNoise == 1)
       d = d + randn(size(d))*5; 
    end
end