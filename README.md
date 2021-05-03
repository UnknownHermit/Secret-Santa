# Secret-Santa
Technical Test for AirAngel

This technical challenge aims to solve the problem of pairing up a provided list of secret santas, initially entirely at random, and then making sure members of the same family members aren't matched. My solution to this problem is primarily back-end focused.

My solution to this problem was to create 2 identical arrays, loop through the first array and assign them someone at random from the second array (ensuring it wasn't themselves), then remove the assigned user from the second array. In the event that we reached the end of the 2nd array and the only person left in it was the person needing someone assigned then a random person from those already assigned was swapped out for them.
When assigning families in addition to the 2 arrays, a 3rd temporary array was created, this was populated from the second array using only people that didn't share the same surname as the person in the first.

My reason for doing it this way was that I felt it was the easiest way to understand the process that was happening in regards to sorting the different users and if I came back to it a long time down the line i'd still be able to understand what was going on with the code.
Architecturally, I attempted to keep the different parts of the system separate. Most of the work is done by the SecretSanta class, which is called by the php files, who use the output from the class to prepare strings for use in the view files. This allowed for the sharing of certain views (view_santas in this case) if certain processes, in the end, required the same visual output.

If I were to spend more time on the project I would add in more error checking and validation all round. I would also find a solution to the potential problem of getting caught in an endless loop when doing family assignments, as currently, if a list had 5 users from 1 family and only 1 from another, it would get caught in the while(!$differentSurname) loop. (This is currently the reason for the low session time limits and the unused $c counters. The API would also use auth keys to make sure whoever was using it actually had access to the API, as currently it just accepts posts.
