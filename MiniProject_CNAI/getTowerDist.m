% Calculates the eucledian distance between towers and walker.
% If third argument is 1, gaussian noise is added to the distances.
function d = getTowerDist(towerCoords, walkerCoords, addNoise)
    d = [];
    
    % For each tower. Take its [X;Y] coordinates and subtract it from all
    % walker coordinates. This creates a collection of vectors between a
    % tower and each walker coordinate. This forms a 2xn matrix of vectors.
    % Caluclate the distance of each vector by calculating the two norm ie
    % eucledian distance.
    % Append the distance to d forming a Nx3 matrix where the rows are the
    % towers, and the colums distances between the walker and tower
    for t = towerCoords'
        towerDistVec = t - walkerCoords';
        d = [d; vecnorm(towerDistVec)];
    end
    
    if(addNoise == 1)
       d = d + randn(size(d))*5; 
    end
end