function loc = trilaterate(towerPos, distance)
    %(Pi - P0)' * (Pi - P0) = ri^2
    
    % Offset coordinates so that first tower is at origin.
    offset = towerPos(1, :);
    offsetTower = towerPos - offset;
    
    % construct Hx=b
    H = offsetTower(2:end, 1:end);
    
    ksqr = sum(H.^2, 2);
    
    b = 1/2 * [ksqr - distance(2:end, 1:end).^2 + distance(1, 1:end).^2 ];
    
    % Solve least square equation and add offset back.
    loc = (H\b) + offset';
    
end