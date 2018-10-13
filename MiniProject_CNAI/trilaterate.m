function loc = trilaterate(towerPos, distance)
    
    % construct Hx=b
    H = towerPos(2:end, 1:end);
    
    ksqr = sum(H.^2, 2);
    
    b = 1/2 * [ksqr - distance(2:end, 1:end).^2 + distance(1, 1:end).^2 ];
    
    loc = H\b;
    
end