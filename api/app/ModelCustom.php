<?php

namespace App;

class ModelCustom
{
    public static function hasManyOnManyServers(string $databaseType, string $related, string $foreignKey, string $localValue)
    {
        $servers = config('dofus.servers');
        $entities = [];

        foreach ($servers as $server)
        {
            $entitiesList = self::hasManyOnOneServer($databaseType, $server, $related, $foreignKey, $localValue);

            foreach ($entitiesList as $entity)
            {
                $entities[] = $entity;
            }
        }

        return $entities;
    }

    public static function hasManyOnOneServer(string $databaseType, string $server, string $related, string $foreignKey, string $localValue)
    {
        $entities = [];
        $entitiesList = $related::on($server . '_' . $databaseType)->where($foreignKey, $localValue)->get();

        foreach ($entitiesList as $entity)
        {
            $entity->server = $server;
            $entities[] = $entity;
        }

        return $entities;
    }

    public static function hasOneOnOneServer(string $databaseType, string $server, string $related, string $foreignKey, string $localValue)
    {
        $entity = $related::on($server . '_' . $databaseType)->where($foreignKey, $localValue)->first();
        $entity->server = $server;

        return $entity;
    }
}
