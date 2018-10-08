clear;

%ask user for the speed (choose greater than 1m/s for now)
prompt = 'Speed value: ';
v = input(prompt);

%ask user for the speed (choose greater than 1m/s for now)
prompt = 'Number of steps: ';
N = input(prompt);

%initialize variables
x=zeros(1450); % x and y set the size of the map
y=zeros(750);
d=[1 1]; % unitary vector for direction, set at (1,1) at the beginning
K=zeros(1000);
plot_data=zeros(N,2);


% Initial position
posX=0;
posY=0;


for K = 1:N
    
    if (posX-v>0) && (posX+v<1450) && (posY-v>0) && (posY+v<750) %check if it is within the bounds
        posX=posX+d(1)*v; %increase the position multiplying the versor with the speed
        posY=posY+d(2)*v;
    else % if it's out of bounds, "reroll" the dice for position in the map
        posX = round(1450*rand(1)); 
        posY = round(750*rand(1));
    end
    
   plot_data(K,1)=posX;
   plot_data(K,2)=posY;
   
    if (rand(1)<=0.05)  %chech the 5% chaces of changing direction every second
        
        % if we are changing direction, roll a dice to set the direcion
        % versor for each of the 8 possible directions:
        %  (-1,1)| (0,1)  | (1,1)
        % -------------------
        %  (-1,0)|   X    | (1,0)
        % -------------------
        % (-1,-1)| (0,-1) | (1,-1)
    
        n = 8*rand(1);
        
        if n<=1 
            d=[-1 -1];
        elseif (n>1)&&(n<=2)
            d=[0 -1];
        elseif (n>2)&&(n<=3)
            d=[1 -1];
        elseif (n>3)&&(n<=4)
            d=[-1 0];
        elseif (n>4)&&(n<=5)
            d=[1 0];
        elseif (n>5)&&(n<=6)
            d=[-1 1];
        elseif (n>6)&&(n<=7)
            d=[0 1];
        elseif (n>7)&&(n<=8)
            d=[1 1];
        end
    end  
end

comet(plot_data(:,1), plot_data(:,2));
