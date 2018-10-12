clear;

Zones = [0 200 550 700; % Zone 1
         550 300 850 700; % Zone 2
         850 100 1050 600; % Zone 3
         1050 250 1450 700; % Zone 4
         350 0 750 200]'; % Zone 5

%ask user for the speed (choose greater than 1m/s for now)
prompt = 'Speed value: ';
v = input(prompt);

%ask user for the number of steps
prompt = 'Number of steps: ';
N = input(prompt);

clf;

%initialize variables
x=zeros(1450); % x and y set the size of the map
y=zeros(750);
d=[1 1]; % unitary vector for direction, set at (1,1) at the beginning
%K=zeros(1000);

% Initial position
posX = round(1450*rand(1)); 
posY = round(750*rand(1));

points = zeros(N, 2);
realZones = zeros(N, 1);
noiseZones = zeros(N, 1);


for k = 1:N
    if (posX+v*d(1)>0) && (posX+v*d(1)<1450) && (posY+v*d(2)>0) && (posY+v*d(2)<750) %check if it is within the bounds
        posX=posX+d(1)*v; %increase the position multiplying the versor with the speed
        posY=posY+d(2)*v;
    else % if it's out of bounds, "reroll" the dice for position in the map
        while (posX+v*d(1)<0) || (posX+v*d(1)>1450) || (posY+v*d(2)<0) || (posY+v*d(2)>750) %if the new direction does not fit, we do it again to chose another one
            d=getNewDir();
        end
        posX=posX+d(1)*v; %increase the position multiplying the versor with the speed
        posY=posY+d(2)*v;
    end
    realZones(k) = getZone(posX,posY);
    
    points(k, 1) = posX;
    points(k, 2) = posY;
    
    if (rand(1)<=0.05)  %check the 5% chaces of changing direction every second
        d=getNewDir();
    end  
end

% Add estimation error
added_error = addError(points, 0, 5);

% Calculate the zones with error
for k = 1:N
   noiseZones(k) = getZone(added_error(k, 1), added_error(k, 2));
end

% Compare new real zone to previous estimated zone. 
% This gives a vector with 1 or 0 for each compared zone.
compare = noiseZones(1:end-1) == realZones(2:end);

correctZoneEst = sum(compare);
incorrectZoneEst = N-correctZoneEst;

% Calculate percent of miss-estimated zones.
miss = incorrectZoneEst/N*100

hold on
plot(points(:,1), points(:,2), '.r');


plot(added_error(:,1), added_error(:,2), '.b');
hold off
drawZones(Zones);
axis([0 1450 0 750])
function newdir=getNewDir()
 % if we are changing direction, roll a dice to set the direcion
        % versor for each of the 8 possible directions:
        %  (-1,1)| (0,1)  | (1,1)
        % -------------------
        %  (-1,0)|   X    | (1,0)
        % -------------------
        % (-1,-1)| (0,-1) | (1,-1)
    n = 8*rand(1);
        
    if n<=1 
        newdir=[-1 -1];
    elseif (n>1)&&(n<=2)
        newdir=[0 -1];
    elseif (n>2)&&(n<=3)
        newdir=[1 -1];
    elseif (n>3)&&(n<=4)
        newdir=[-1 0];
    elseif (n>4)&&(n<=5)
        newdir=[1 0];
    elseif (n>5)&&(n<=6)
        newdir=[-1 1];
    elseif (n>6)&&(n<=7)
        newdir=[0 1];
    elseif (n>7)&&(n<=8)
        newdir=[1 1];
    end
end

function zone= getZone(posx,posy) % gets the zone with the position.
    if ((posx>0)&&(posx<=550)&&(posy>200)&&(posy<=700))
        zone=1;
    elseif ((posx>550)&&(posx<=850)&&(posy>300)&&(posy<=700))
        zone=2;
    elseif ((posx>850)&&(posx<=1050)&&(posy>100)&&(posy<=600))
        zone=3;
    elseif ((posx>1050)&&(posx<=1450)&&(posy>250)&&(posy<=700))
        zone=4;
    elseif ((posx>350)&&(posx<=750)&&(posy>0)&&(posy<=200))
        zone=5;
    else
        zone=0;
    end
end

function points_error = addError(data, mean, variance)
    points_error = data + (randn(length(data), 2)*variance+mean);
end

% Draw zones and zone numbers.
function drawZones(zones)
hold on
    zoneNumber = 1;
    for z = zones
        rectangle('Position',[z(1:2)' (z(3:4)-z(1:2))']);
        txt = sprintf('Zone: %d', zoneNumber);
        midPoint = z(1:2)./2+z(3:4)./2;
        text(midPoint(1)-50,midPoint(2), txt);
        zoneNumber = zoneNumber + 1;
    end

hold off
end