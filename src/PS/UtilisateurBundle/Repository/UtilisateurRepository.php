<?php

namespace PS\UtilisateurBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UtilisateurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UtilisateurRepository extends EntityRepository
{
    /**
     * @param $id
     * @param $email
     * @return mixed
     */
    public function updateEmailByParent($id, $email)
    {
        $pdo = $this->getEntityManager()->getConnection();

        $canonicalize = function ($string) {

        };

        $sql = <<<SQL
UPDATE utilisateur
SET email = :email
, email_canonical = :email_canonical
WHERE id <> :id
AND parent_id = :parent
SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('email', $email);
        $stmt->bindValue('email_canonical', $this->canonicalize($email));
        $stmt->bindValue('id', $id);
        $stmt->bindValue('parent', $id);
        return $stmt->execute();
    }

    /**
     * @param $utilisateur
     */
    public function updateSmsCode($utilisateur)
    {
        $pdo = $this->getEntityManager()->getConnection();
        $id  = $utilisateur->getId();
        $sql = "UPDATE utilisateur SET sms_code = NULL WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('id', $id);
         return $stmt->execute();
    }


    public function setSmsCode($utilisateur)
    {
        $str = '';
        $alphabet = '0123456789';
        for ($i = 0; $i < 6; ++$i) {
            $str .= $alphabet[random_int(0, $alphamax)];
        }

        $pdo = $this->getEntityManager()->getConnection();
        $id  = $utilisateur->getId();
        $sql = "UPDATE utilisateur SET sms_code = :sms_code WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('sms_code', $alphabet);
        $stmt->bindValue('id', $id);
        return $stmt->execute();
    }

    /**
     * {@inheritdoc}
     */
    private function canonicalize($string)
    {
        $encoding = mb_detect_encoding($string);
        $result   = $encoding
        ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
        : mb_convert_case($string, MB_CASE_LOWER);

        return $result;
    }

    /**
     * @param $user
     * @param $result
     * @param false $api
     * @return mixed
     */
    public function associes($user, $result = false, $api = false, $limit = null, $offset = null)
    {
        $qb   = $this->createQueryBuilder('u');
        $stmt = $qb;

        $stmt->andWhere('u.parent = :parent')
            ->andWhere('u.email = :email')
            ->setParameter('parent', $user->getId())
            ->setParameter('email', $user->getEmail());

        if (!$result) {
            return $stmt;
        }

        $stmt->setFirstResult($offset)
            ->setMaxResults($limit);

        return $stmt->getQuery()->getResult();
    }
}
