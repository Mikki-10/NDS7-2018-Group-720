% Prompts the user for N locations on the plot.
% The locations will be plotted as red X.
% Returns the coordinates of picked locations.
function pos = pickPosition(N)
    pos = zeros(N, 2);
    hold on
    for i = 1:N
        [x, y] = ginput(1);
        pos(i, 1) = x;
        pos(i, 2) = y;
        
        txt = sprintf('Tower %d', i);
        plot(x, y, 'xr', 'DisplayName', txt)
    end
    legend
    hold off
end