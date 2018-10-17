%%
%plot changes of speed

    %ask user for the number of steps
prompt = 'Number of steps for speed changes plots : ';
N = input(prompt);

    %ask user for the interval for estimation
prompt= 'Interval between 2 estimations for speed changes plots : ' ; 
freqEst=input(prompt);

    %ask user for the noise
prompt = 'Noise in the antennas : ';
noise = input(prompt);

speedErrors=zeros(5,1);
for speed=1:5
    for i=1:100
        error=estimatedError(speed,N,freqEst, noise);
        speedErrors(speed)=speedErrors(speed)+error/100;
    end
end

figure(1);
plot(1:5,speedErrors);
xlabel('Speed of the walker');
ylabel('mmPr (%)');
%%
%plot changes of estimation time

%ask user for the speed (choose greater than 1m/s for now)
prompt = 'Speed value for the change of the interval between 2 estimations : ';
v = input(prompt);

%ask user for the number of steps
prompt = 'Number of steps for the change of the interval between 2 estimations : ';
N = input(prompt);

%ask user for the noise
prompt = 'Noise in the antennas ';
noise = input(prompt);

freqEstErrors=zeros(20,1);
for freqEst=1:20
    for i=1:100
        error=estimatedError(v,N,freqEst, noise);
        freqEstErrors(freqEst)=freqEstErrors(freqEst)+error/100;
    end
end
figure(2);
plot(1:20,freqEstErrors);
xlabel('Time between updates');
ylabel('mmPr (%)');
%
%%
%plot changes of noise
%plot changes of estimation time

%ask user for the speed (choose greater than 1m/s for now)
prompt = 'Speed value for noise plots : ';
v = input(prompt);

%ask user for the number of steps
prompt = 'Number of steps for noise plots : ';
N = input(prompt);

%ask user for the interval for estimation
prompt= 'Interval between 2 estimations noise plots : ' ; 
freqEst=input(prompt);

noiseErrors=zeros(15,1);
for noise=1:15
    for i=1:100
        error=estimatedError(v,N,freqEst, noise);
        noiseErrors(noise)=noiseErrors(noise)+error/100;
    end
end

figure(3);
plot(1:15,noiseErrors);
xlabel('Variance of Gaussian Noise in antena estimation');
ylabel('mmPr (%)');
%%
function error= estimatedError(v, N, freqEst, noise_variance)
    Zones = [0 200 550 700; % Zone 1
         550 300 850 700; % Zone 2
         850 100 1050 600; % Zone 3
         1050 250 1450 700; % Zone 4
         350 0 750 200]'; % Zone 5
    towerx=rand(3,1)*1450;
    towery=rand(3,1)*750;
    towerPosititons=[towerx towery];
    %initialize variables
    x=zeros(1450); % x and y set the size of the map
    y=zeros(750);
    d=[1 1]; % unitary vector for direction, set at (1,1) at the beginning
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
    %doing the vector for the estimation (taking only 1 sample over freqEst
    %samples in the vector)
    pointsForEstimation=zeros(floor(N/freqEst),2);
    for k=0:N-1
       if (k/freqEst==floor(k/freqEst))
           pointsForEstimation(k/freqEst+1,1)=points((k+1),1);
           pointsForEstimation(k/freqEst+1,2)=points((k+1),2);
       end
    end
    
    distances = getTowerDist(towerPosititons, pointsForEstimation, 1,noise_variance);
    tLocationEst = trilaterate(towerPosititons, distances)';

    tLocation=zeros(N,2);
    %double the samples so they coincide in time with the real positions
    for j=0:N-1
      if (j/freqEst==floor(j/freqEst))
          tLocation(j+1,1)=tLocationEst(j/freqEst+1,1);
          tLocation(j+1,2)=tLocationEst(j/freqEst+1,2);
      else
          tLocation(j+1,1)=tLocationEst(floor(j/freqEst)+1,1);
            tLocation(j+1,2)=tLocationEst(floor(j/freqEst)+1,2);
      end
    end
    
    % Calculate the zones with error
    noiseZones=zeros(N,1);
    for k = 1:N
       noiseZones(k) = getZone(tLocation(k, 1), tLocation(k, 2));
    end
    
    % Compare new real zone to previous estimated zone. 
    % This gives a vector with 1 or 0 for each compared zone.
    compare = noiseZones(1:end-1) == realZones(2:end);

    correctZoneEst = sum(compare);
    incorrectZoneEst = N-correctZoneEst;

    % Calculate percent of miss-estimated zones.
    miss = incorrectZoneEst/N*100;
    error=miss;
    
end
