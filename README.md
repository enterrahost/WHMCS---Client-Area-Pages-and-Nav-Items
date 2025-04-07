# WHMCS-Client-Area-Pages-and-Nav-Items
WHMCS hooks that manage visibility and access to the Affiliates section of the WHMCS client area. These hooks are ideal for businesses who want to restrict their affiliate program to a specific client group, or hide it entirely for guests or non-approved clients / not logged in visitors
This can also be addapted to hide or display other parts of the client area like, submit ticket, knowledgebase, Network Status etc. 
The limitation of this is that the WHMCS only allows 1 clients group, you should however be able to match multiple groups, as an example "Affilaites Group" and "Staff - Also Affilites" groupid=1,10
$allowedGroupIds = [1, 10]; // Add any other group IDs here <-- look this these lines in the code.

## - Enterrahost
