SELECT u.name, c.name, p.name
FROM community_members cm
         JOIN users u on cm.user_id = u.id
         JOIN communities c on c.id = cm.community_id
         JOIN community_member_permissions cmp on cm.id = cmp.member_id
         JOIN permissions p on p.id = cmp.permission_id
WHERE LENGTH(c.name) >= 15
  AND (p.name LIKE BINARY '%articles%' OR u.name LIKE '%t%');