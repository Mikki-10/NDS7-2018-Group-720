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