<?php

function DateInterval_to_seconds($dateInterval)
      {
        return ($dateInterval->y * 365 * 24 * 60 * 60) +
               ($dateInterval->m * 30 * 24 * 60 * 60) +
               ($dateInterval->d * 24 * 60 * 60) +
               ($dateInterval->h * 60 * 60) +
               ($dateInterval->i * 60) +
               $dateInterval->s;
      } 
      
      function TimeInterval_to_seconds($timeInterval)
      {
      	return ($dateInterval->h * 60 * 60) +
      	($dateInterval->i * 60) +
      	$dateInterval->s;
      }
?>