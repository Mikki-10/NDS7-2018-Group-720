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