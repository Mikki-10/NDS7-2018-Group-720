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