function filtered = filterLog(messages, msg_string, data)
    counter = 1;
    for i = 1:length(messages)
        str_contents = strfind(messages(i,:), msg_string);
        if length(str_contents) > 0
            filtered(counter) = data(i);
            counter = counter + 1;
        end
    end
end