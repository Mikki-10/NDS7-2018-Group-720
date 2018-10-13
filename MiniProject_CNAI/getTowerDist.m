function d = getTowerDist(towerCoords, walkerCoords, addNoise)
    
    d = [];
    for t = towerCoords'
        
        towerDistVec = t - walkerCoords';
        d = [d; vecnorm(towerDistVec)];
    end
end